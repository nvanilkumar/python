import pyautogui
import time

pyautogui.hotkey('win', 'd');
pyautogui.doubleClick(106 ,451);

print("This prints once a minute.")
time.sleep(3) 
print("action done");
#pyautogui.moveTo(100, 100, duration=0.25)
pyautogui.doubleClick(255 ,154);
time.sleep(5) 
pyautogui.typewrite('Silence$90');
pyautogui.hotkey('enter');
print("enter done");

#create screen shot
time.sleep(3)
im1 = pyautogui.screenshot(region=(532,334,888,368))
im2 = pyautogui.screenshot('my_screenshot.png')
print("image done");

#pyautogui.doubleClick(270 ,144);
#106 451
#270 144
''' 

 
'''	

'''
for i in range(10):
	pyautogui.moveTo(100, 100, duration=0.25)
	pyautogui.moveTo(200, 100, duration=0.25)
	pyautogui.moveTo(200, 200, duration=0.25)
	pyautogui.moveTo(100, 200, duration=0.25)
'''	
