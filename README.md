# Doctor Appointment Booking System

## Introduction
This project is a web-based appointment booking system designed to solve the challenges faced by patients in Zimbabwe's healthcare sector. The system provides an easy-to-use platform for patients to schedule appointments with doctors, view doctors' schedules, and receive confirmations.

## Features
- **User Registration and Login**: Patients can register and log in to the system.
- **Appointment Booking**: Patients can select a doctor, date, and time to book an appointment.
- **Email Notifications**: Patients receive email notifications upon successful booking.
- **Admin Review**: Doctors can review and update the status of appointments in the admin panel.
- **Doctor Registration and Login**: Doctors can register and log in to the system.
- **Doctor Dashboard**: Doctors can view and manage their appointments.

## Recent Changes
- **Admin Dashboard**: Added an admin dashboard for managing doctors and users.
- **Feedback Functionality**: Implemented feedback functionality for doctors.
- **User Roles and Statuses**: Updated user roles and statuses.

## Admin Credentials
- **Username**: admin
- **Password**: admin123

## Directory Structure
- `admin_dashboard.php`: Admin dashboard for managing doctors and users.
- `user_dashboard.php`: User dashboard displaying user profile and appointments.
- **Front-End Development**: HTML, CSS, and JavaScript for creating a responsive and visually appealing user interface.
- **Back-End Development**: PHP for server-side logic, handling user authentication, appointment scheduling, and data retrieval.
- **Local Development Environment**: XAMPP for setting up an Apache server, PHP interpreter, and MySQL database.
- **Database**: MySQL for managing user accounts, doctor information, and appointment details.
- **Security**: PHP-based encryption libraries for secure storage and management of sensitive data.

## Directory Structure
- `index.php`: Main entry point of the application.
- `login.php`: User login page.
- `register.php`: User registration page.
- `make_appointment.php`: Page for booking appointments.
- `doctor_login.php`: Doctor login page.
- `doctor_dashboard.php`: Doctor dashboard for managing appointments.
- `db.php`: Database connection file.
- `style.css`: CSS file for styling.
- `script.js`: JavaScript file for client-side functionality.
- `images/`: Directory for storing images.

## Installation
1. **Clone the Repository**:
   ```sh
   git clone https://github.com/yourusername/doctor-appointment-booking-system.git
   cd doctor-appointment-booking-system
   ```

2. **Set Up XAMPP**:
   - Install XAMPP from the official website.
   - Start the Apache and MySQL services.

3. **Import the Database**:
   - Open phpMyAdmin.
   - Create a new database named `doctor_appointment`.
   - Import the `mysql.sql` file into the database.

4. **Configure Database Connection**:
   - Open `db.php` and update the database connection details if necessary.

5. **Run the Application**:
   - Open a web browser and navigate to `http://localhost/TDS`.

## Usage
1. **User Registration**:
   - Navigate to `register.php` and fill in the registration form.
   - Click "Register" to create an account.

2. **User Login**:
   - Navigate to `login.php` and enter your credentials.
   - Click "Login" to access the system.

3. **Booking an Appointment**:
   - Navigate to `make_appointment.php`.
   - Select a doctor, date, and time.
   - Click "Make Appointment" to book the appointment.

4. **Doctor Registration**:
   - Navigate to `doctor_login.php` and click "Register" to create a doctor account.

5. **Doctor Login**:
   - Navigate to `doctor_login.php` and enter your credentials.
   - Click "Login" to access the doctor dashboard.

6. **Managing Appointments**:
   - In the doctor dashboard, view and manage your appointments.
   - Update the status of appointments as needed.

## Project Completion Steps

1. **Project Setup**:
   - Install XAMPP to set up a local development environment.
   - Start the Apache and MySQL services in XAMPP.
   - Create a new database using the provided `mysql.sql` file.

2. **Database Configuration**:
   - Execute the `mysql.sql` file in the MySQL environment to create the necessary tables for users, doctors, and appointments.

3. **Front-End Development**:
   - Create HTML pages for user registration, login, appointment booking, and viewing schedules.
   - Use CSS for styling the application to ensure a responsive and visually appealing design.
   - Implement JavaScript for client-side validation and interactivity.

4. **Back-End Development**:
   - Develop PHP scripts for:
     - User registration and authentication (`register.php`, `login.php`).
     - Appointment booking logic (`make_appointment.php`, `book_appointment.php`).
     - Retrieving available doctors and their schedules (`get_available_doctors.php`, `get_availability.php`).
     - Admin functionalities for managing appointments (`doctor_dashboard.php`).

5. **Testing**:
   - Conduct unit testing for individual components.
   - Perform integration testing to ensure all parts of the application work together seamlessly.
   - Gather user feedback to identify areas for improvement.

6. **Deployment**:
   - Prepare the application for deployment on a web server.
   - Ensure all configurations are set for production, including security measures for user data.

7. **Documentation**:
   - Update the `README.md` file with installation instructions, usage guidelines, and any other relevant information for users and developers.

## Contributing

## License

## Contact
For any questions or feedback, please contact [your email].

## Acknowledgments
- [XAMPP](https://www.apachefriends.org/index.html)
- [phpMyAdmin](https://www.phpmyadmin.net/)
- [PHP](https://www.php.net/)
- [MySQL](https://www.mysql.com/)
- [HTML, CSS, JavaScript](https://developer.mozilla.org/)
