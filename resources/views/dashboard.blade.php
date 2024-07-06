<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <script>
        // Initialize Pusher
        Pusher.logToConsole = true;
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
        });

        const channel = pusher.subscribe('mqtt-channel');
        channel.bind('mqtt-event', function(data) {
            console.log('Real-time data received:', data);
            updateTable(data);
        });

        // Initialize MQTT client
        const client = mqtt.connect('ws://202.157.186.97:9001', {
            username: 'pablo',
            password: 'costa'
        });

        client.on('connect', () => {
            console.log('Connected to MQTT broker');
            client.subscribe('publish/topic', (err) => {
                if (err) {
                    console.error('Subscription error:', err);
                } else {
                    console.log('Subscribed to publish/topic');
                }
            });
        });

        client.on('message', (topic, message) => {
            if (topic === 'publish/topic') {
                const data = JSON.parse(message.toString());
                console.log('Data received from MQTT:', data);
                updateTable(data);
            }
        });

        function updateTable(data) {
            const tableBody = document.getElementById('data-table-body');
            let row = document.getElementById(`row-${data.kendaraan_id}`);
            if (!row) {
                row = document.createElement('tr');
                row.id = `row-${data.kendaraan_id}`;
                tableBody.appendChild(row);
            }
            row.innerHTML = `
                <td>${data.kendaraan_id}</td>
                <td>${data.longitude}</td>
                <td>${data.latitude}</td>
                <td>${data.status}</td>
                <td>
                    <button onclick="sendControlMessage(${data.kendaraan_id}, 'aktif')">1</button>
                    <button onclick="sendControlMessage(${data.kendaraan_id}, 'nonaktif')">0</button>
                </td>
            `;
        }

        function sendControlMessage(kendaraanId, status) {
            fetch('/control', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ kendaraan_id: kendaraanId, status: status })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Control message sent:', data);
                if (data.success) {
                    client.publish('control/topic', status);
                }
            })
            .catch(error => {
                console.error('Error sending control message:', error);
            });
        }
    </script>
</head>
<body>
    <h1>Dashboard</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="data-table-body">
            <!-- Data rows will be inserted here by JavaScript -->
        </tbody>
    </table>
</body>
</html>
