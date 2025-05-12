import adafruit_dht
import board
import time
import os

# Sensor data pin is connected to GPIO 23
sensor = adafruit_dht.DHT11(board.D23)

# Check if the file exists before opening it in 'a' mode (append mode)
file_exists = os.path.isfile('sauvegarde_DHT11.txt')

# Use a context manager to handle the file
with open('sauvegarde_DHT11.txt', 'a') as file:
    # Write the header to the file if the file does not exist
    if not file_exists:
        file.write('Time and Date, Temperature (ºC), Humidity (%)\n')

    running = True
    # loop forever
    while running:
        try:
            # read the temperature
            temperature = sensor.temperature

            # read the humidity
            humidity = sensor.humidity

            # print readings on the shell
            print("Temp={}ºC".format(temperature) + " Humidity={} %".format(humidity))

            # save time, date, temperature, and humidity in .txt file
            file.write(time.strftime('%H:%M:%S %d/%m/%Y') + ', {:.2f}, {:.2f}\n'.format(temperature, humidity))
            file.flush()  # Ensure data is written to the file immediately

            # log new readings every 10 seconds
            time.sleep(10)

        except RuntimeError as error:
            # Errors happen fairly often, DHT's are hard to read, just keep going
            print(error.args[0])
            time.sleep(2.0)
            continue

        except KeyboardInterrupt:
            print('Program stopped')
            running = False
