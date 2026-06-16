import cv2
import numpy as np
import os
import time
import base64
from config import get_db_connection

class FaceRecognitionSystem:
    def __init__(self):
        self.face_cascade = cv2.CascadeClassifier(
            cv2.data.haarcascades + 'haarcascade_frontalface_default.xml'
        )
        self.recognizer = cv2.face.LBPHFaceRecognizer_create()
        self.dataset_dir = 'dataset'
        self.trained_model_path = 'trained_model.yml'
        
        if not os.path.exists(self.dataset_dir):
            os.makedirs(self.dataset_dir)
            
    def capture_face_data_from_images(self, student_id, condition_id, images_data):
        captured = 0
        student_dir = os.path.join(self.dataset_dir, str(student_id))
        
        if not os.path.exists(student_dir):
            os.makedirs(student_dir)
            
        for i, img_data in enumerate(images_data):
            # Decode base64 image
            if img_data.startswith('data:image'):
                img_data = img_data.split(',')[1]
            
            img_bytes = base64.b64decode(img_data)
            nparr = np.frombuffer(img_bytes, np.uint8)
            img = cv2.imdecode(nparr, cv2.IMREAD_COLOR)
            
            if img is None:
                continue
                
            gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
            faces = self.face_cascade.detectMultiScale(gray, 1.3, 5)
            
            for (x, y, w, h) in faces:
                img_name = os.path.join(
                    student_dir,
                    f"student_{student_id}_cond_{condition_id}_{captured+1}.jpg"
                )
                cv2.imwrite(img_name, gray[y:y+h, x:x+w])
                captured += 1
                
                self.save_to_database(student_id, condition_id, img_name)
                break  # Only take the first face
                
        print(f"Captured {captured} images!")
        return captured
    
    def capture_face_data(self, student_id, condition_id, num_images=5):
        cap = cv2.VideoCapture(0)
        captured = 0
        student_dir = os.path.join(self.dataset_dir, str(student_id))
        
        if not os.path.exists(student_dir):
            os.makedirs(student_dir)
            
        print(f"Starting capture for student {student_id}, condition {condition_id}...")
        
        while captured < num_images:
            ret, frame = cap.read()
            if not ret:
                break
                
            gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
            faces = self.face_cascade.detectMultiScale(gray, 1.3, 5)
            
            for (x, y, w, h) in faces:
                cv2.rectangle(frame, (x, y), (x+w, y+h), (255, 0, 0), 2)
                
                img_name = os.path.join(
                    student_dir,
                    f"student_{student_id}_cond_{condition_id}_{captured+1}.jpg"
                )
                cv2.imwrite(img_name, gray[y:y+h, x:x+w])
                captured += 1
                
                self.save_to_database(student_id, condition_id, img_name)
                
            cv2.imshow('Capture Face', frame)
            
            if cv2.waitKey(100) & 0xFF == ord('q'):
                break
                
        cap.release()
        cv2.destroyAllWindows()
        print(f"Captured {captured} images!")
        return captured
    
    def save_to_database(self, student_id, condition_id, image_path):
        conn = get_db_connection()
        if conn:
            try:
                cursor = conn.cursor()
                query = """
                    INSERT INTO face_data (student_id, condition_id, image_path)
                    VALUES (%s, %s, %s)
                """
                cursor.execute(query, (student_id, condition_id, image_path))
                conn.commit()
            except Exception as e:
                print(f"Error saving to database: {e}")
            finally:
                cursor.close()
                conn.close()
    
    def train_model(self):
        faces = []
        ids = []
        
        conn = get_db_connection()
        if not conn:
            return False
            
        try:
            cursor = conn.cursor()
            cursor.execute("SELECT student_id, image_path FROM face_data")
            results = cursor.fetchall()
            
            for student_id, image_path in results:
                if os.path.exists(image_path):
                    img = cv2.imread(image_path, 0)
                    faces.append(np.array(img, 'uint8'))
                    ids.append(int(student_id))
            
            if len(faces) > 0:
                self.recognizer.train(faces, np.array(ids))
                self.recognizer.save(self.trained_model_path)
                
                cursor.execute("UPDATE face_data SET is_training = 1")
                conn.commit()
                print("Model trained successfully!")
                return True
            else:
                print("No training data found!")
                return False
        except Exception as e:
            print(f"Error training model: {e}")
            return False
        finally:
            cursor.close()
            conn.close()
    
    def load_model(self):
        if os.path.exists(self.trained_model_path):
            self.recognizer.read(self.trained_model_path)
            return True
        return False
    
    def recognize_face(self, frame, conditions=None):
        start_time = time.time()
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        faces = self.face_cascade.detectMultiScale(gray, 1.3, 5)
        
        results = []
        
        for (x, y, w, h) in faces:
            id, confidence = self.recognizer.predict(gray[y:y+h, x:x+w])
            confidence = 100 - confidence
            
            latency = (time.time() - start_time) * 1000
            
            student_name = self.get_student_name(id)
            
            result = {
                'student_id': id,
                'student_name': student_name,
                'confidence': round(confidence, 2),
                'latency': round(latency, 3),
                'conditions': conditions or {}
            }
            results.append(result)
            
        return results
    
    def get_student_name(self, student_id):
        conn = get_db_connection()
        if conn:
            try:
                cursor = conn.cursor()
                cursor.execute("SELECT name FROM students WHERE id = %s", (student_id,))
                result = cursor.fetchone()
                return result[0] if result else "Unknown"
            except Exception as e:
                print(f"Error getting student name: {e}")
                return "Unknown"
            finally:
                cursor.close()
                conn.close()
        return "Unknown"
    
    def save_experiment_log(self, student_id, actual_identity, predicted_identity, 
                           confidence, latency, conditions, experiment_type):
        conn = get_db_connection()
        if conn:
            try:
                cursor = conn.cursor()
                is_correct = 1 if actual_identity == predicted_identity else 0
                
                query = """
                    INSERT INTO experiment_logs 
                    (student_id, actual_identity, predicted_identity, confidence, latency,
                     light_condition, face_angle, distance_condition, is_correct, experiment_type)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
                """
                cursor.execute(query, (
                    student_id, actual_identity, predicted_identity, confidence, latency,
                    conditions.get('light'), conditions.get('angle'), conditions.get('distance'),
                    is_correct, experiment_type
                ))
                conn.commit()
            except Exception as e:
                print(f"Error saving experiment log: {e}")
            finally:
                cursor.close()
                conn.close()
    
    def calculate_metrics_incremental(self):
        conn = get_db_connection()
        if not conn:
            return None
            
        try:
            cursor = conn.cursor(dictionary=True)
            
            # Ambil semua experiment log testing yang belum diproses (atau ambil semuanya)
            cursor.execute("""
                SELECT id, student_id, actual_identity, predicted_identity, confidence, latency,
                       light_condition, face_angle, distance_condition, is_correct, created_at
                FROM experiment_logs 
                WHERE experiment_type = 'testing'
                ORDER BY id ASC
            """)
            logs = cursor.fetchall()
            
            # Kosongkan evaluation metrics dulu untuk mulai fresh
            cursor.execute("DELETE FROM evaluation_metrics")
            conn.commit()
            
            total_tests = 0
            correct_predictions = 0
            false_accept = 0
            false_reject = 0
            true_positive = 0
            false_positive = 0
            false_negative = 0
            latencies = []
            
            for log in logs:
                total_tests += 1
                latencies.append(log['latency'] or 0)
                
                if log['is_correct'] == 1:
                    correct_predictions += 1
                    if log['predicted_identity'] is not None:
                        true_positive +=1
                else:
                    if log['predicted_identity'] is not None:
                        false_accept +=1
                        false_positive +=1
                    else:
                        false_reject +=1
                        false_negative +=1
                
                accuracy = (correct_predictions / total_tests * 100) if total_tests > 0 else 0
                precision = (true_positive / (true_positive + false_positive) * 100) if (true_positive + false_positive) > 0 else 0
                recall = (true_positive / (true_positive + false_negative) * 100) if (true_positive + false_negative) > 0 else 0
                far = (false_accept / total_tests * 100) if total_tests > 0 else 0
                frr = (false_reject / total_tests * 100) if total_tests > 0 else 0
                avg_latency = sum(latencies)/len(latencies) if latencies else 0
                
                # Insert ke evaluation_metrics
                query = """
                    INSERT INTO evaluation_metrics 
                    (`total_tests`, `correct_predictions`, `accuracy`, `precision`, `recall`, `far`, `frr`, `avg_latency`, `experiment_log_id`)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
                """
                cursor.execute(query, (
                    total_tests, correct_predictions,
                    round(accuracy,2), round(precision,2), round(recall,2),
                    round(far,2), round(frr,2), round(avg_latency,3),
                    log['id']
                ))
                conn.commit()
            
            # Ambil metrics terakhir untuk return
            cursor.execute("SELECT * FROM evaluation_metrics ORDER BY id DESC LIMIT 1")
            metrics = cursor.fetchone()
            
            return metrics
        except Exception as e:
            import traceback
            print(f"Error calculating metrics: {e}")
            print(traceback.format_exc())
            return None
        finally:
            cursor.close()
            conn.close()
    
    def calculate_metrics(self):
        return self.calculate_metrics_incremental()

    def calculate_metrics_by_condition(self, light_condition=None, face_angle=None, distance_condition=None):
        conn = get_db_connection()
        if not conn:
            return None

        try:
            cursor = conn.cursor(dictionary=True)

            # Build WHERE clause
            where_clauses = ["experiment_type = 'testing'"]
            params = []

            if light_condition:
                where_clauses.append("light_condition = %s")
                params.append(light_condition)
            if face_angle:
                where_clauses.append("face_angle = %s")
                params.append(face_angle)
            if distance_condition:
                where_clauses.append("distance_condition = %s")
                params.append(distance_condition)

            where_str = " AND ".join(where_clauses)

            # Total tests
            cursor.execute(f"SELECT COUNT(*) as total FROM experiment_logs WHERE {where_str}", params)
            total_tests = cursor.fetchone()['total']

            # Correct predictions
            cursor.execute(f"SELECT COUNT(*) as correct FROM experiment_logs WHERE {where_str} AND is_correct = 1", params)
            correct_predictions = cursor.fetchone()['correct']

            # False Accept (incorrect but predicted as someone)
            cursor.execute(f"SELECT COUNT(*) as false_accept FROM experiment_logs WHERE {where_str} AND is_correct = 0 AND predicted_identity IS NOT NULL", params)
            false_accept = cursor.fetchone()['false_accept']

            # False Reject (correct but predicted as null)
            cursor.execute(f"SELECT COUNT(*) as false_reject FROM experiment_logs WHERE {where_str} AND is_correct = 0 AND predicted_identity IS NULL", params)
            false_reject = cursor.fetchone()['false_reject']

            # Avg latency
            cursor.execute(f"SELECT AVG(latency) as avg_latency FROM experiment_logs WHERE {where_str}", params)
            avg_latency = cursor.fetchone()['avg_latency'] or 0

            # True Positive
            cursor.execute(f"SELECT COUNT(*) as true_positive FROM experiment_logs WHERE {where_str} AND is_correct = 1 AND predicted_identity IS NOT NULL", params)
            true_positive = cursor.fetchone()['true_positive']

            # False Positive
            cursor.execute(f"SELECT COUNT(*) as false_positive FROM experiment_logs WHERE {where_str} AND is_correct = 0 AND predicted_identity IS NOT NULL", params)
            false_positive = cursor.fetchone()['false_positive']

            # False Negative
            cursor.execute(f"SELECT COUNT(*) as false_negative FROM experiment_logs WHERE {where_str} AND is_correct = 0 AND predicted_identity IS NULL", params)
            false_negative = cursor.fetchone()['false_negative']

            accuracy = (correct_predictions / total_tests * 100) if total_tests > 0 else 0
            precision = (true_positive / (true_positive + false_positive) * 100) if (true_positive + false_positive) > 0 else 0
            recall = (true_positive / (true_positive + false_negative) * 100) if (true_positive + false_negative) > 0 else 0
            far = (false_accept / total_tests * 100) if total_tests > 0 else 0
            frr = (false_reject / total_tests * 100) if total_tests > 0 else 0

            return {
                'total_tests': total_tests,
                'correct_predictions': correct_predictions,
                'accuracy': round(accuracy, 2),
                'precision': round(precision, 2),
                'recall': round(recall, 2),
                'far': round(far, 2),
                'frr': round(frr, 2),
                'avg_latency': round(float(avg_latency), 3)
            }
        except Exception as e:
            import traceback
            print(f"Error calculating metrics by condition: {e}")
            print(traceback.format_exc())
            return None
        finally:
            cursor.close()
            conn.close()
