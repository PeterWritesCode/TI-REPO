#include <math.h>
#include <TinyGPSPlus.h>
#include <SoftwareSerial.h>
#include <ArduinoMqttClient.h>
#include <WiFi.h>
#include "network.h"
char* ssid = SSID;
char* pass = PASS; 

WiFiClient wifiClient;
MqttClient mqttClient(wifiClient);

//set interval for sending messages (milliseconds)
const long interval = 1000;
unsigned long previousMillisQueda = 0;
unsigned long previousMillisGPS = 0;

//const char broker[] = "test.mosquitto.org";
 char* broker = "broker.hivemq.com";
int port = 1883;
const char topic[] = "spadaNotifs";

const int x_out = 35; /* connect x_out of module to A1 of UNO board */
const int y_out = 32; /* connect y_out of module to A2 of UNO board */
const int z_out = 33; /* connect z_out of module to A3 of UNO board */

static const int TXPin = 32, RXPin = 35;
static const uint32_t GPSBaud = 9600;
TinyGPSPlus gps;
SoftwareSerial ss(RXPin, TXPin);

void setup()
{

  Serial.begin(115200);
  while (!Serial) {
    ; // wait for serial port to connect. Needed for native USB port only
  }
  pinMode(RXPin,INPUT);
  pinMode(TXPin,OUTPUT);
  
  ss.begin(GPSBaud);
  

  Serial.print("Attempting to connect to WPA SSID: ");
  Serial.println(ssid);
  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED) {
    // failed, retry
    Serial.print(".");
    delay(5000);
  }

  Serial.println("You're connected to the network");
  Serial.println();
  Serial.print("Attempting to connect to the MQTT broker: ");
  Serial.println(broker);

  if (!mqttClient.connect(broker, port)) {
    Serial.print("MQTT connection failed! Error code = ");
    Serial.println(mqttClient.connectError());

    while (1);
  }

  Serial.println("You're connected to the MQTT broker!");
 

}

void loop()
{
  // This sketch displays information every time a new sentence is correctly encoded.
  mqttClient.poll();
  int x_adc_value, y_adc_value, z_adc_value; 
  double x_g_value, y_g_value, z_g_value;

  //x_adc_value = analogRead(x_out); /* Digital value of voltage on x_out pin */ 
  //y_adc_value = analogRead(y_out); /* Digital value of voltage on y_out pin */ 
  z_adc_value = analogRead(z_out); /* Digital value of voltage on z_out pin */ 
  
  //x_g_value = ( ( ( (double)(x_adc_value * 3.3)/4096) - 1.65 ) / 0.330 ); /* Acceleration in x-direction in g units */ 
 // y_g_value = ( ( ( (double)(y_adc_value * 3.3)/4096) - 1.65 ) / 0.330 ); /* Acceleration in y-direction in g units */ 
  

  


  
      // save the last time a message was sent
  
  while(ss.available() > 0){
      z_adc_value = analogRead(z_out); /* Digital value of voltage on z_out pin */
      z_g_value = ( ( ( (double)(z_adc_value * 3.3)/4096) - 1.80 ) / 0.330 ); /* Acceleration in z-direction in g units */ 
      if(z_g_value > 4.5 || z_g_value < -4.5){
        Serial.println(z_g_value);
        mqttClient.beginMessage(topic);
        mqttClient.print("queda");
        mqttClient.endMessage();
        delay(500);
      }
    

      unsigned long currentMillisGPS = millis();
      while(gps.encode(ss.read())){
        
          if(currentMillisGPS - previousMillisGPS >= 60000){
            mqttClient.beginMessage(topic);
            sendInfo();
            displayInfo();
            mqttClient.endMessage();
            previousMillisGPS = currentMillisGPS;
          }
         
          
          
        
        
      }
      
    
    
    }
    

  /*if (millis() > 5000 && gps.charsProcessed() < 10)
  {
    mqttClient.beginMessage(topic);
    mqttClient.print("NO GPS");
    Serial.println(F("No GPS detected: check wiring."));
    sleep(5);
    
    mqttClient.endMessage();
  }*/

}
void displayInfo()
{

  Serial.print(F("Location: ")); 
  if (gps.location.isValid())
  {
    Serial.print(gps.location.lat(), 6);
    Serial.print(F(","));
    Serial.print(gps.location.lng(), 6);
  }
  else
  {
    Serial.print(F("INVALID"));
  }

  Serial.print(F("  Date/Time: "));
  if (gps.date.isValid())
  {
    Serial.print(gps.date.month());
    Serial.print(F("/"));
    Serial.print(gps.date.day());
    Serial.print(F("/"));
    Serial.print(gps.date.year());
  }
  else
  {
    Serial.print(F("INVALID"));
  }

  Serial.print(F(" "));
  if (gps.time.isValid())
  {
    if (gps.time.hour() < 10) Serial.print(F("0"));
    Serial.print(gps.time.hour());
    Serial.print(F(":"));
    if (gps.time.minute() < 10) Serial.print(F("0"));
    Serial.print(gps.time.minute());
    Serial.print(F(":"));
    if (gps.time.second() < 10) Serial.print(F("0"));
    Serial.print(gps.time.second());
    Serial.print(F("."));
    if (gps.time.centisecond() < 10) Serial.print(F("0"));
    Serial.print(gps.time.centisecond());
  }
  else
  {
    Serial.print(F("INVALID"));
  }

  Serial.println();
}

void sendInfo(){
  String latlng = "";
  String date = "";
  String time = "";
  mqttClient.print("GPS Location: ");
  if (gps.location.isValid()){
    latlng.concat(gps.location.lat());
    latlng.concat(",");
    latlng.concat(gps.location.lng());
    mqttClient.print(latlng);
  } else{
    mqttClient.print("INVALID");
  }
  mqttClient.print("Data: ");
  if (gps.date.isValid())
  {
    date.concat(gps.date.day());
    date.concat("/");
    date.concat(gps.date.month());
    date.concat("/");
    date.concat(gps.date.year());
    mqttClient.print(date);
  }else{
    mqttClient.print("INVALID");
  }
  mqttClient.print("Time: ");
  if(gps.time.isValid()){
    if (gps.time.hour() < 10) Serial.print(F("0"));
    time.concat(gps.time.hour());
    time.concat(":");
    if (gps.time.minute() < 10) Serial.print(F("0"));
    time.concat(gps.time.minute());
    time.concat(":");
    if (gps.time.second() < 10) Serial.print(F("0"));
    time.concat(gps.time.second());
    time.concat(".");
    mqttClient.print(time);
  } else {
    mqttClient.print("INVALID");
  }

}