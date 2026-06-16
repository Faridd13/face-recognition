from config import get_db_connection

conn = get_db_connection()
if conn:
    try:
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM students")
        students = cursor.fetchall()
        print("Students in Database:")
        print("------------------")
        for student in students:
            print(student)
            
        cursor.close()
        conn.close()
    except Exception as e:
        import traceback
        traceback.print_exc()
