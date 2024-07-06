<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MQTT Data</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@7.2.3/dist/pusher.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="data-table">
            @foreach($kendaraan as $k)
                <tr data-id="{{ $k->id }}">
                    <td>{{ $k->nama }}</td>
                    <td id="longitude-{{ $k->id }}">{{ $k->longitude }}</td>
                    <td id="latitude-{{ $k->id }}">{{ $k->latitude }}</td>
                    <td id="status-{{ $k->id }}">{{ $k->status }}</td>
                    <td>
                        <button id="id-update" onclick="updateStatus({{ $k->id }})"></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Setup Pusher
            const pusher = new Pusher('7f26ee5fa85ac1038bf4', {
                cluster: 'ap1',
                encrypted: true
            });
    
            const channel = pusher.subscribe('kendaraan-channel');
            channel.bind('App\\Events\\KendaraanStatusUpdated', function(data) {
                console.log('Kendaraan status updated:', data.kendaraan);
    
                // Update UI with new status
                const kendaraan = data.kendaraan;
                const statusElement = document.getElementById(`status-${kendaraan.id}`);
                if (statusElement) {
                    statusElement.innerText = kendaraan.status;
                    const button = statusElement.nextElementSibling; // Assuming the button is next to the status
                    if (button) {
                        button.innerText = kendaraan.status === 'aktif' ? 'Deactivate' : 'Activate';
                    }
                }
            });
    
            const broker = "ws://202.157.186.97:9001";
            const username = "pablo";
            const password = "costa";
            const controlTopic = "control/topic";
            const publishTopic = "publish/topic";
            const responseTopic = "response/topic";
    
            // Connect to MQTT broker
            const client = mqtt.connect(broker, {
                username: username,
                password: password
            });
    
            client.on("connect", () => {
                console.log("Connected to MQTT broker");
                client.subscribe([publishTopic, responseTopic]);
            });
    
            client.on("message", (topic, message) => {
                try {
                    const data = JSON.parse(message.toString());
    
                    if (topic === publishTopic) {
                        // Handle incoming MQTT data and send it to Laravel for storage
                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        fetch('/mqtt/store-data', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(responseData => {
                            if (responseData.status === 'success') {
                                console.log('Data stored successfully:', responseData.data);
                                const longitudeElement = document.getElementById(`longitude-${data.kendaraan_id}`);
                                const latitudeElement = document.getElementById(`latitude-${data.kendaraan_id}`);
                                if (longitudeElement && latitudeElement) {
                                    longitudeElement.innerText = responseData.data.longitude || 'N/A';
                                    latitudeElement.innerText = responseData.data.latitude || 'N/A';
                                }
                            } else {
                                console.error('Failed to store data:', responseData.message);
                            }
                        })
                        .catch(error => console.error('Fetch error:', error));
                    } else if (topic === responseTopic) {
                        // Update longitude and latitude on receiving response data
                        if (data && data.kendaraan_id) {
                            const longitudeElement = document.getElementById(`longitude-${data.kendaraan_id}`);
                            const latitudeElement = document.getElementById(`latitude-${data.kendaraan_id}`);
                            if (longitudeElement && latitudeElement) {
                                longitudeElement.innerText = data.longitude || 'N/A';
                                latitudeElement.innerText = data.latitude || 'N/A';
                            } else {
                                console.error(`Elements for kendaraan_id ${data.kendaraan_id} not found.`);
                            }
                        } else {
                            console.error('Invalid data format:', data);
                        }
                    }
                } catch (error) {
                    console.error('Message parsing error:', error);
                }
            });
    
            window.updateStatus = function (id) {
                const statusElement = document.getElementById(`status-${id}`);
                const buttonElement = document.getElementById('id-update');
                const currentStatus = statusElement.innerText;
                const newStatus = currentStatus === 'aktif' ? 'nonaktif' : 'aktif';
                statusElement.innerText = newStatus;
                buttonElement.innerText = newStatus === 'aktif'?'nonaktif':'aktif';
    
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
                fetch(`/mqtt/update-status/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(() => {
                    client.publish(controlTopic, newStatus);
                })
                .catch(error => console.error('Fetch error:', error));
            };
        });
    </script>
    
</body>

</html
