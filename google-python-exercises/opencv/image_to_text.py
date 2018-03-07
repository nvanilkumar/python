import pytesseract
from PIL import Image, ImageEnhance, ImageFilter
from pytesseract import image_to_string

im = Image.open("mvc.png") # the second one 
#im = im.convert('RGBA')
#im = im.filter(ImageFilter.ModeFilter(0))

 
 
enhancer = ImageEnhance.Contrast(im)
im = enhancer.enhance(5)
im = im.convert('1')
 

im.save('2result.png')
pytesseract.pytesseract.tesseract_cmd = 'C:\\Program Files (x86)\\Tesseract-OCR\\tesseract.exe'
#text = pytesseract.image_to_string(Image.open('2result.png')).encode("utf-8")
#tessdata_dir_config = '--tessdata-dir "C:\\Program Files (x86)\\Tesseract-OCR\\tesseract"'
#print(pytesseract.image_to_string(Image.open("2result.png"), lang='eng', config = tessdata_dir_config))
print(pytesseract.image_to_string(Image.open("2result.png")))
#text = pytesseract.image_to_string(Image.open("2result.png"),lang='eng',config=tessdata_dir_config))
#.encode("utf-8")
#print(text)