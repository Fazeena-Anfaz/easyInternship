# Project Report: Antigravity Learning & Simulation System (ALSS)
**Target: 3rd Year Undergraduate Academic Project**

## 1. Executive Summary
The Antigravity Learning & Simulation System (ALSS) is a specialized educational portal designed to bridge the gap between theoretical physics and student understanding. By utilizing a structured database-driven approach, the system transforms scattered theoretical concepts into organized, interactive learning modules supplemented by visual simulations.

## 2. System Architecture
The project utilizes a **Modular 3-Tier Architecture**:
- **Client Tier**: Responsive HTML5/CSS3 frontend with high-end glassmorphism for enhanced student engagement.
- **Server Tier**: PHP 8.1+ handle the Core logic, Session Management, and RBAC (Role-Based Access Control).
- **Database Tier**: MySQL (MariaDB) stores normalized educational data, ensuring ACID compliance for student records and content.

## 3. Database Schema (Enhanced)
The database is designed for high integrity and scalability.
- `users`: Core authentication data.
- `topics`: Primary educational content (Title, Description, Category).
- `simulations`: Links visual assets/animations to specific topics.
- `learning_materials`: File metadata for PDFs and reference documents.
- `feedback`: Student interaction and peer-review system.

## 4. Module-wise Description
- **I. User Authentication Module**: Implements secure SHA-256/Bcrypt hashing for multi-role access (Admin/Student).
- **II. Educational Repository**: A structured library where students can browse topics by categories (e.g., Quantum Levitation, Alcubierre Theory).
- **III. Simulation Interface**: Uses animations and descriptive data to visualize "non-intuitive" physics concepts.
- **IV. Student Portfolio**: Allows users to "Save as Favorite" and provide feedback on specific learning materials.
- **V. Content Management (Admin)**: Full CRUD operations for topics, simulations, and user moderation.

## 5. Data Flow (Process Model)
1. **Entry**: Student logs in; system identifies role and redirects to the Student Dashboard.
2. **Browsing**: Student selects "Quantum Gravity" from the repository.
3. **Execution**: System fetches topic data + associated simulation + PDF materials from the database.
4. **Interaction**: Student leaves a comment or saves the topic to their "Favorites" list.
5. **Update**: System logs the interaction in the `feedback` and `favorites` tables.

## 6. Project Scope & Future Scope
- **Current**: Educational visualization and content management.
- **Future**: Integration of Three.js for real-time 3D simulations and an AI-driven physics tutor.
