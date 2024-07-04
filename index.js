// Import the mqtt library
const mqtt = require('mqtt');

// MQTT broker URL and port
const brokerUrl = 'mqtt://202.157.186.97:1883';

// Options for connecting to the broker
const options = {
  clientId: 'mqtt_subscriber',
  clean: true, // Set to false to receive QoS 1 and 2 messages while offline
  connectTimeout: 4000,
  username: 'pablo',
  password: 'costa',
  reconnectPeriod: 1000, // Reconnect period in milliseconds
};

// Create a client and connect to the broker
const client = mqtt.connect(brokerUrl, options);

// Event handler for successful connection
client.on('connect', () => {
  console.log('Connected to MQTT broker');

  // Subscribe to the topic
  const topic = 'vehicle_tracking';
  client.subscribe(topic, (err) => {
    if (!err) {
      console.log(`Subscribed to topic: ${topic}`);
    } else {
      console.log(`Failed to subscribe to topic: ${topic}`, err);
    }
  });
});

// Event handler for incoming messages
client.on('message', (topic, message) => {
  console.log(`Received message from topic ${topic}: ${message.toString()}`);
});

// Event handler for errors
client.on('error', (err) => {
  console.error('MQTT client error:', err);
});

// Event handler for connection close
client.on('close', () => {
  console.log('Disconnected from MQTT broker');
});
