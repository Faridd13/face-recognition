from face_recognition_system import FaceRecognitionSystem
import os
import cv2

# Initialize system
fr = FaceRecognitionSystem()

print("=== Testing Training ===")
result = fr.train_model()
print(f"Training result: {result}")

print("\n=== Checking Model ===")
model_loaded = fr.load_model()
print(f"Model loaded: {model_loaded}")

# Let's try to load one of the saved images
test_img_path = "dataset/1/student_1_cond_1_1.jpg"
if os.path.exists(test_img_path):
    print(f"\n=== Testing Recognition on Image: {test_img_path} ===")
    img = cv2.imread(test_img_path)
    results = fr.recognize_face(img)
    print(f"Recognition results: {results}")
