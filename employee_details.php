<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Employee details</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body { font-family: Arial; padding: 20px; }
    h2 { margin-top: 40px; }
    input, select, button { margin: 5px 0; padding: 8px; width: 100%; max-width: 300px; }
    .section { border: 1px solid #ccc; padding: 20px; margin-bottom: 40px; border-radius: 8px; }
    table { border-collapse: collapse; width: 100%; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
  </style>
</head>
<body>

  <h1>Employee detaills Dashboard</h1>

  <div class="section">
    <h2>Add Employee</h2>
    <input type="text" id="emp_name" placeholder="Name">
    <input type="email" id="emp_email" placeholder="Email">
    <input type="text" id="emp_position" placeholder="Position">
    <input type="number" id="emp_salary" placeholder="Salary">
    <button onclick="addEmployee()">Add</button>

    <h2>All Employees</h2>
    <table id="employeeTable">
      <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Position</th><th>Salary</th><th>Action</th></tr></thead>
      <tbody></tbody>
    </table>
  </div>



  <script>
    function addEmployee() {
      const data = new FormData();
      data.append('add_employee', '1');
      data.append('name', document.getElementById('emp_name').value);
      data.append('email', document.getElementById('emp_email').value);
      data.append('position', document.getElementById('emp_position').value);
      data.append('salary', document.getElementById('emp_salary').value);

      fetch('employee.php', { method: 'POST', body: data })
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          loadEmployees();
        });
    }

    function deleteEmployee(id) {
      const data = new FormData();
      data.append('delete_employee', '1');
      data.append('id', id);

      fetch('employee.php', { method: 'POST', body: data })
        .then(res => res.text())
        .then(msg => {
          alert(msg);
          loadEmployees();
        });
    }

    function loadEmployees() {
      fetch('employee.php')
        .then(res => res.json())
        .then(data => {
          const tbody = document.querySelector('#employeeTable tbody');
          const select = document.getElementById('employeeSelect');
          tbody.innerHTML = '';
          select.innerHTML = '<option disabled selected>Select Employee</option>';

          data.forEach(emp => {
            tbody.innerHTML += `<tr>
              <td>${emp.id}</td>
              <td>${emp.name}</td>
              <td>${emp.email}</td>
              <td>${emp.position}</td>
              <td>${emp.salary}</td>
              <td><button onclick="deleteEmployee(${emp.id})">Delete</button></td>
            </tr>`;
            select.innerHTML += `<option value="${emp.id}">${emp.name}</option>`;
          });
        });
    }



    loadEmployees();

  </script>
</body>
</html>
