======================================================
         Web Development (Enterprise)- Assignment 2
======================================================

Student Name: Aderinboye Ayomide Emmanuel
Student ID:   100994931
Course:       Web Development (Enterprise)
Date:         26 March 2026

------------------------------------------------------
1. System Overview & Architecture
------------------------------------------------------
This application is a secure full-stack mail dashboard utilizing a three-tier 
microservice architecture running on Docker containers:

  - Frontend: React application (Single Page Application).
  - Auth Microservice: Node.js service running on port 8000. It reads 
    from 'users.txt' and signs a JWT token using HS256 encryption.
  - API Microservice: Apache & PHP service talking to a PostgreSQL 
    database. Uses 'Firebase/JWT' to verify incoming tokens.
  - Database: PostgreSQL storing the mail table.

------------------------------------------------------
2. Role-Based Access Control (RBAC) Implementation
------------------------------------------------------
Role-Based Access Control is enforced dynamically using the JWT payloads:

  - Standard Users (e.g., user1): When a user logs in, PHP inspects the 
    decoded token role. Queries are filtered using an SQL 'WHERE' clause 
    (WHERE userid = :userId). Users can only see mail they authored.
    
  - Admin Users (e.g., admin1): If the token role is 'admin', PHP bypasses 
    the scoping filter and pulls the complete table (SELECT * FROM mail). 
    Admins can see messages from all users.

------------------------------------------------------
3. Installation and Deployment Setup
------------------------------------------------------
Prerequisites: Docker Desktop installed and running.

Step 1: Unzip the project folder. 
Step 2: Open terminal (PowerShell or Bash) in the project root directory.
Step 3: Run the build command:
        
        docker-compose up --build

Step 4: Navigate to the application in your browser:
        
        http://localhost:3000

------------------------------------------------------
4. Standard Test Accounts (Seeded via init.sql / users.txt)
------------------------------------------------------
Use the credentials below to verify standard vs elevated views:

  - Username: user1
    Password: 12345
    Role: Standard User (Isolated inbox)

  - Username: admin1
    Password: 333
    Role: Administrator (Global inbox visibility)

------------------------------------------------------
5. Excluded Heavy Library Folders
------------------------------------------------------
To ensure standard submission file sizes, binary and heavy dependency 
library folders were intentionally omitted from the zipped package. 
They will automatically regenerate using standard deployment trackers.

  - /node/node_modules/ (Regenerated via internal Node package.json)
  - /vendor/ (Regenerated via Composer autoloading)
======================================================