from face_recognition_system import FaceRecognitionSystem

face_system = FaceRecognitionSystem()
print("Calling calculate_metrics()...")
metrics = face_system.calculate_metrics()
print(f"Result: {metrics}")
