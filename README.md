# Journ Blog App

## Description
A blog application developed for **IN2120 Web Programming** at the **University of Moratuwa** using:

- **Frontend:** HTML, CSS, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL  

This project allows users to register, log in, and manage their own blogs with full CRUD functionality.  

---

## Features

- **User Authentication**
  - Register, log in, log out
  - Only authenticated users can create, update, or delete their own blogs
  - Users cannot edit or delete blogs by others

- **Blog Management**
  - Add new blogs using a Markdown editor
  - View all blogs on the home page
  - View a single blog with author and date
  - Update or delete your own blogs

- **Frontend**
  - Clean and responsive UI
  - Blog editor for creating and updating posts

---

## Database Tables

- `user` (id, username, email, password, role)  
- `blogPost` (id, user_id, title, content, created_at, updated_at)  

---

## Hosted Site

[View Live](https://journ.lovestoblog.com)  

---

