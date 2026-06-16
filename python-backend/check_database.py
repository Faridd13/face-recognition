import os
from config import get_db_connection

conn = get_db_connection()
if conn:
    try:
        cursor = conn.cursor()
        cursor.execute("SELECT student_id, image_path FROM face_data")
        results = cursor.fetchall()
        
        print("Face Data in Database:")
        print("-----------------------")
        for student_id, image_path in results:
            absolute_path = os.path.abspath(image_path)
            exists = os.path.exists(absolute_path)
            print(f"Student {student_id}: {image_path} Exists? {exists} (abs path: {absolute_path})")
            
        cursor.close()
        conn.close()
    except Exception as e:
        import traceback
        traceback.print_exc()
