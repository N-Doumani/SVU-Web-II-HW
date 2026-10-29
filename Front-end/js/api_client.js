// Modular API Client for Backend Integration
const API_BASE = 'api'; // Change to relative or absolute server path when linking backend PHP

const StudentAPI = {
    // 1. Fetch Students List
    async getStudents(page = 1, search = '') {
        try {
            const response = await fetch(`${API_BASE}/view_students.php?page=${page}&search=${encodeURIComponent(search)}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching students:', error);
            return null;
        }
    },

    // 2. Add New Student
    async addStudent(formData) {
        try {
            const response = await fetch(`${API_BASE}/add_student.php`, {
                method: 'POST',
                body: formData // FormData handles file upload automatically
            });
            return await response.json();
        } catch (error) {
            console.error('Error adding student:', error);
            return null;
        }
    },

    // 3. Update Student
    async updateStudent(formData) {
        try {
            const response = await fetch(`${API_BASE}/update_student.php`, {
                method: 'POST',
                body: formData
            });
            return await response.json();
        } catch (error) {
            console.error('Error updating student:', error);
            return null;
        }
    },

    // 4. Delete Student
    async deleteStudent(studentId) {
        try {
            const response = await fetch(`${API_BASE}/delete_student.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: studentId })
            });
            return await response.json();
        } catch (error) {
            console.error('Error deleting student:', error);
            return null;
        }
    }
};
