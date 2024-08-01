<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Conditional Input</title>
<script>
  function toggleInput() {
    var selectValue = document.getElementById("mySelect").value;
    var inputField = document.getElementById("myInput");
    
    // Check if the select value is 1
    if (selectValue === "1") {
      inputField.style.display = "block"; // Show the input field
    } else {
      inputField.style.display = "none"; // Hide the input field
    }
  }
</script>
</head>
<body>

<select id="mySelect" onchange="toggleInput()">
  <option value="0">Select Value</option>
  <option value="1">Show Input</option>
  <option value="2">Hide Input</option>
</select>

<input type="text" id="myInput" style="display: none;" placeholder="Enter something">

</body>
</html>
