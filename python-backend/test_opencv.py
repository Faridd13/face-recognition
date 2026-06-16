import cv2
print("OpenCV version:", cv2.__version__)

try:
    print("Trying cv2.face.LBPHFaceRecognizer_create()...")
    recognizer = cv2.face.LBPHFaceRecognizer_create()
    print("SUCCESS: Using cv2.face.LBPHFaceRecognizer_create()")
except AttributeError as e:
    print(f"ERROR: {e}")
    try:
        print("Trying cv2.face.LBPHFaceRecognizer.create()...")
        recognizer = cv2.face.LBPHFaceRecognizer.create()
        print("SUCCESS: Using cv2.face.LBPHFaceRecognizer.create()")
    except AttributeError as e:
        print(f"ERROR: {e}")
        try:
            print("Trying cv2.LBPHFaceRecognizer_create()...")
            recognizer = cv2.LBPHFaceRecognizer_create()
            print("SUCCESS: Using cv2.LBPHFaceRecognizer_create()")
        except AttributeError as e:
            print(f"ERROR: {e}")
            try:
                print("Trying cv2.LBPHFaceRecognizer.create()...")
                recognizer = cv2.LBPHFaceRecognizer.create()
                print("SUCCESS: Using cv2.LBPHFaceRecognizer.create()")
            except AttributeError as e:
                print(f"ERROR: {e}")
                print("ERROR: Could not create LBPHFaceRecognizer")
