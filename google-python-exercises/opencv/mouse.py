from PIL import Image
#from pytesseract import image_to_string
#import pytesseract
from pytesser import *


im = Image.open('my_screenshot.png')
#im = Image.open('test.png')
pytesseract.pytesseract.tesseract_cmd = 'C:/Program Files (x86)/Tesseract-OCR/tesseract'

#C:\Program Files (x86)\Tesseract-OCR
'''
#from pytesser import *

print image_to_string(Image.open('E:\myhost\google-python-exercises\my_screenshot.png'))
#print image_to_string(Image.open('test-english.jpg'), lang='eng')
'''

#print(im)
i=pytesseract.image_to_string(im)
text = image_file_to_string(image_file, graceful_errors=True)
print text;
with open('magic.txt',mode='w') as file:
	#file.write(i.encode("utf-8"))
	file.write(i.encode('ascii', 'ignore').decode('ascii'))
	
	print(' > see the magic file')
#print(i)
print("end");

''''
import pyautogui

print('Press Ctrl-C to quit.')

try:
	while True:
 		# Get and print the mouse coordinates.
 		x, y = pyautogui.position()
 		positionStr = 'X: ' + str(x).rjust(4) + ' Y: ' + str(y).rjust(4) 
 		print(positionStr)
 		#print('\b' * len(positionStr), end='', flush=True)
except KeyboardInterrupt:
	print('\nDone.')
'''	