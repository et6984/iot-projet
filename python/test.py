import os
f = open('C:/wamp64/www/iot-projet/test/RGPD.txt', 'a', encoding='utf-8')
f.write('\nUn traitement de donnée doit avoir un objetif')
f.close()
f = open('C:/wamp64/www/iot-projet/test/RGPD.txt', 'r', encoding='utf-8')
print(f.read())