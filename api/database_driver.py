import os
from dotenv import load_dotenv
import mysql.connector
from mysql.connector import Error

# Load environment variables from .env file
load_dotenv()

DB_HOST = os.getenv('DB_HOST')
DB_USER = os.getenv('DB_USER')
DB_PASSWORD = os.getenv('DB_PASSWORD')
DB_NAME = os.getenv('DB_NAME')


def get_db_connection():
    try:
        connection = mysql.connector.connect(
            host=DB_HOST,
            user=DB_USER,
            password=DB_PASSWORD,
            database=DB_NAME
        )
        if connection.is_connected():
            return connection
    except Error as e:
        print(f"Error connecting to MySQL: {e}")
        return None

def sample_query():
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT 1 as test")
        result = cursor.fetchone()
        cursor.close()
        conn.close()
        return result
    return None

def validate_device(device_id: str) -> bool:
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor()
        cursor.execute("SELECT COUNT(*) FROM smart_meters WHERE device_id = %s", (device_id,))
        exists = cursor.fetchone()[0] > 0
        cursor.close()
        conn.close()
        return exists
    return False

def get_last_update_id(device_id: str) -> int:
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor()
        cursor.execute("SELECT last_update_id FROM smart_meters WHERE device_id = %s", (device_id,))
        row = cursor.fetchone()
        cursor.close()
        conn.close()
        if row:
            return row[0]
    return 0

def create_update(device_id: str, units: int, new_update_id: int) -> bool:
    conn = get_db_connection()
    if conn:
        cursor = conn.cursor()
        cursor.execute(
            "INSERT INTO updates (device_id, units, update_id, status) VALUES (%s, %s, %s, 'pending')",
            (device_id, units, new_update_id)
        )
        conn.commit()
        success = cursor.rowcount > 0
        cursor.close()
        conn.close()
        return success
    return False
