'''
python string.py ssrc=1073937685;themssrc=1261535777;lp=0;rxjitter=0.000000;rxcount=585;txjitter=0.008053;txcount=486;rlp=0;rtt=0.020401

--------------------------------------------------------------------------


' Take the average latency, add jitter, but double the impact to latency
' then add 10 for protocol latencies
EffectiveLatency = ( AverageLatency + Jitter * 2 + 10 )

' Implement a basic curve - deduct 4 for the R value at 160ms of latency
' (round trip).  Anything over that gets a much more agressive deduction
if EffectiveLatency < 160 then
   R = 93.2 - (EffectiveLatency / 40)
else
   R = 93.2 - (EffectiveLatency - 120) / 10

' Now, let's deduct 2.5 R values per percentage of packet loss
R = R - (PacketLoss * 2.5)

' Convert the R into an MOS value.(this is a known formula)
MOS = 1 + (0.035)  R + (.000007)  R  (R-60)  (100-R)

-------------------------------------------------------------------------------------------------------------
AvgLatency  = RTT * 1000 ms
Packet Loss = rlp
Jitter = txjitter
jitter = jitter * 1000
'''

import sys
import MySQLdb

inputstring = str(sys.argv[1])

items=inputstring.split(';')

#print items
jitter =0
avgLatency=0
packetLoss=0
for i in items:
	if i.startswith( 'rtt=' ):
	 	avgLatency=float(i[4:]) * 1000
	if i.startswith( 'txjitter=' ):
	 	jitter=float(i[9:]) * 1000 	
	if i.startswith( 'rlp=' ):
	 	packetLoss=float(i[4:])

#print avgLatency
#print jitter
#print "jitter:"+	jitter 	+"# avgLatency: "+avgLatency	+"# packetLoss :"+packetLoss
effectiveLatency =  ( avgLatency + jitter * 2 + 10 )

if (effectiveLatency < 160) :
	r = 93.2 - (effectiveLatency / 40)
else :
	r = 93.2 - ((effectiveLatency - 120) / 40)	

r = r - (packetLoss * 2.5)	

mos = 1 + (0.035  * r) + (.000007 * r) * (r-60) * (100-r)

print "***************"	
print " R  value: " 
print r
print "MOS value:  " + str(mos)



 

db = MySQLdb.connect(host="localhost",    # your host, usually localhost
                     user="root",         # your username
                     passwd="root",  # your password
                     db="world")        # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need

x = db.cursor()
try:
	print "db"
 
	x.execute('''INSERT into mos (input_string, r, mos) values (%s, %s, %s)''', (inputstring, r, mos))
	db.commit()
	print "end db"
except:
   db.rollback()

db.close()




 
