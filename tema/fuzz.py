import socket

IP='127.0.0.1'
PORT=27015

buffer=["A"]
counter=10
while len(buffer) <= 10:
    buffer.append("A"*counter)
    counter=counter+10

print "Connecting to %s:%d" % (IP, PORT)
s=socket.socket(socket.AF_INET, socket.SOCK_STREAM)
connect=s.connect((IP, PORT))
print "...Connected"    
s.send('user test')
print s.recv(1024)

for string in buffer:
    print "Fuzzing with %s bytes" % len(string)
    s.send('pass ' + string)
    print s.recv(1024)

s.send('exit')
s.close()