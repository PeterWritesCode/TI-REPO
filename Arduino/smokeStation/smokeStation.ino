#include <ArduinoMqttClient.h>
#include <WiFi.h>
#include "network.h"
char* ssid = SSID;
char* pass = PASS; 

WiFiClient wifiClient;
MqttClient mqttClient(wifiClient);

//const char broker[] = "test.mosquitto.org";
 char* broker = "broker.hivemq.com";
int port = 1883;

const char topic[] = "spadaNotifs";
int count = 0;
const int LED = 2; 
const int DO = 33; 
const int AO = 32;
const long interval = 8000;
float R0;
int start = 0;
unsigned long calibrationTime = 15000; //15 minutos, mudar depois
unsigned long previousMillis = 0;
float sensorValue; 

void setup() { 
  Serial.begin(9600);

  pinMode(LED, OUTPUT); 
  pinMode(DO, INPUT); 
  start = millis();
  while(millis()-start < calibrationTime){
     float sensor_volt;  
    float RS_air; //  Rs in clean air
    
  
    //Average   
    for(int x = 0 ; x < 100 ; x++) 
    { 
      sensorValue = sensorValue + analogRead(AO); 
      Serial.println(analogRead(AO));
    } 
    sensorValue = sensorValue/100.0; 
    //-----------------------------------------------/ 
  
    sensor_volt = (sensorValue/1024)*5.0; 
    RS_air = (5.0-sensor_volt)/sensor_volt; // Depend on RL on yor module 
    R0 = RS_air/9.9; // According to MQ9 datasheet table 
    
    Serial.print("sensor_volt = "); 
    Serial.print(sensor_volt); 
    Serial.println("V");
    
    Serial.print("R0 = "); 
    Serial.println(R0); 
    delay(1000) ;
    }
    Serial.print("A tentar a conecção: ");
    Serial.println(ssid);
    Serial.println(pass);
    WiFi.begin(ssid, pass);

    while(WiFi.status() != WL_CONNECTED) {
      //failed, retry

      delay(5000);
      Serial.print(".");
      
      

    }
   
    
    Serial.println("Connected!\n");
    Serial.print("A tentar a conecção a MQTT: ");
    Serial.println(broker);

    if(!mqttClient.connect(broker,port)){
      Serial.print("MQTT connection failed! ERROR = ");
      Serial.println(mqttClient.connectError());

      while(1);
    }
    
    Serial.println("You're connected to the MQTT broker!");
    Serial.println();
} 
 
void loop() { 
  mqttClient.poll();

 

 
  int alarm = 0; 
  float sensor_volt; 
  float RS_gas; 
  float ratio; 
  Serial.println(analogRead(AO));
  sensorValue = analogRead(AO); 
  sensor_volt = ((float)sensorValue / 1024) * 5.0; 
  RS_gas = (5.0 - sensor_volt) / sensor_volt; // Depend on RL on yor module 
 
 
  ratio = RS_gas / R0; // ratio = RS/R0 
 //------------------------------------------------------------/ 
  Serial.println(sensorValue);
  Serial.print("sensor_volt = "); 
  Serial.println(sensor_volt); 
  Serial.print("RS_ratio = "); 
  Serial.println(RS_gas); 
  Serial.print("Rs/R0 = "); 
  Serial.println(ratio); 
 
  Serial.print("\n\n"); 

  if (ratio < 0){
    digitalWrite(LED, HIGH); 
    
    unsigned long currentMillis = millis();
    if (currentMillis - previousMillis >= interval){
      previousMillis = currentMillis;
      
      mqttClient.beginMessage(topic);
      mqttClient.print("Gas");
      mqttClient.endMessage();
    }
  } 
  else  digitalWrite(LED, LOW); 
 
  delay(1000); 
}