import processing.serial.*;

import processing.video.*;
Capture video;

import mqtt.*;
Serial port;
MQTTClient client;
String ID="spadaNotifs";

float UVCutoff = 7;
boolean messagePublished = false;

ArrayList<Particle> allParticles = new ArrayList<Particle>();
float currentHue = 0;
boolean flagParticles = true;
int maxParticles = 80000;
int noParticlesTimer = 0;
int noParticlesCount = 0;
float tone;


//Particula individual
class Particle {
  PVector pos;
  PVector vel;
  float h;

  Particle(float x, float y, float velX, float velY) {
    pos = new PVector(x, y);
    vel = new PVector(velX, velY);
    h = currentHue;
  }

  void move() {
    vel.mult(0.94);
    pos.add(vel);
  }
}

void setup() {
  size(640, 480);
  colorMode(RGB);
  video = new Capture(this, 640, 480);
  video.start();

  //MQTT COMUNICATION
  client = new MQTTClient(this);
  // client.connect("tcp://broker.hivemq.com:1883", "spadaNotifs");

  try {
    client.connect("tcp://broker.hivemq.com:1883", "spadaNotifs");
    println("Connected to MQTT broker");
  }
  catch (Exception e) {
    println("Failed to connect to MQTT broker: " + e.getMessage());
  }
}

//programa a funcionar
void draw() {
  if (video.available()) {
    video.read();
    image(video, 0, 0);
    AdvanceBuffer();
    ArrayList<FlowZone> fZones = calculateFlow(lastImageData, currentImageData, 640, 480);
    for (FlowZone fz : fZones) {
      fz.draw();
    }
    for (int i = allParticles.size()-1; i > -1; i--) {
      Particle p = allParticles.get(i);
      if (p.vel.mag() < 1) {
        allParticles.remove(p);
        continue;
      }
      p.move();
      stroke(p.h, 360, 360);
      strokeWeight(p.vel.mag()*1.25);
      point(p.pos.x, p.pos.y);
    }
    fill(0);
    noStroke();
  }

  // MENSAGEM APOS TEMPO
  //println("Particles detected: " + allParticles.size() + " tempo passado: " + noParticlesTimer);
  if ((allParticles.size() <= 20) && (flagParticles)) {
    noParticlesTimer = millis();
    flagParticles = !flagParticles;
  } else if (allParticles.size() > 0) {
    noParticlesTimer = millis();
    messagePublished = false;
  }

  if (millis() - noParticlesTimer > 10000) { // 2 minutes
      fill(0);
      textSize(32);
      text("No motion detected in [10 seconds]!", 50, height / 2);
    if (!messagePublished) { // Check if the message has not been published yet
      client.publish(ID, "No Motion detected for: 10 seconds");
      println("Message published successfully");
      messagePublished = true; // Set the flag to true after publishing the message
    }
  }
}

void messageReceived(String topic, byte[] payload) {
  println("new message: " + topic + " - " + new String(payload));
}






PImage lastImageData;
PImage currentImageData;

void AdvanceBuffer() {
  lastImageData = currentImageData;
  currentImageData = get();
}

ArrayList<FlowZone> calculateFlow(PImage oldImage, PImage newImage, int width, int height) {
  ArrayList<FlowZone> zones = new ArrayList();
  if (oldImage == null) return zones;

  int step = 8;
  int winStep = step * 2 + 1;

  float A2, A1B2, B1, C1, C2;
  float u, v, uu, vv;
  uu = vv = 0;
  int wMax = width - step - 1;
  int hMax = height - step - 1;
  int globalY, globalX, localY, localX;

  for (globalY = step + 1; globalY < hMax; globalY += winStep) {
    for (globalX = step + 1; globalX < wMax; globalX += winStep) {
      A2 = A1B2 = B1 = C1 = C2 = 0;

      for (localY = -step; localY <= step; localY++) {
        for (localX = -step; localX <= step; localX++) {
          int address = (globalY + localY) * width + globalX + localX;

          float gradX = (newImage.pixels[address - 1] - newImage.pixels[address + 1]) * 0.5;
          float gradY = (newImage.pixels[address - width] - newImage.pixels[address + width]) * 0.5;
          float gradT = (oldImage.pixels[address] - newImage.pixels[address]) * 0.5;

          A2 += gradX * gradX;
          A1B2 += gradX * gradY;
          B1 += gradY * gradY;
          C2 += gradX * gradT;
          C1 += gradY * gradT;
        }
      }

      float delta = (A1B2 * A1B2 - A2 * B1);

      if (delta != 0) {
        float Idelta = step / delta;
        float deltaX = -(C1 * A1B2 - C2 * B1);
        float deltaY = -(A1B2 * C2 - A2 * C1);
        u = deltaX * Idelta;
        v = deltaY * Idelta;
      } else {
        float norm = (A1B2 + A2) * (A1B2 + A2) + (B1 + A1B2) * (B1 + A1B2);
        if (norm != 0) {
          float IGradNorm = step / norm;
          float temp = -(C1 + C2) * IGradNorm;
          u = (A1B2 + A2) * temp;
          v = (B1 + A1B2) * temp;
        } else {
          u = v = 0;
        }
      }

      if (-winStep < u&& u < winStep &&
        -winStep < v && v < winStep) {
        uu += u;
        vv += v;
        zones.add(new FlowZone(globalX, globalY, u, v));
      }
    }
  }

  return zones;
}

class FlowZone {
  float X, Y, U, V;

  FlowZone(float x, float y, float u, float v) {
    X = x;
    Y = y;
    U = u;
    V = v;
  }

  void draw() {
    if ((abs(U) + abs(V)) / 2.0 < UVCutoff) return;
    tone = random(150, 255);
    int tom = int(tone);
    currentHue = blue(tom);
    if (allParticles.size() < maxParticles) {
      allParticles.add(new Particle(X, Y, U, V));
    }
  }
}
