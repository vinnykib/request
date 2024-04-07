window.addEventListener("load",function(){
    var tabs = document.querySelectorAll(".settings-tabs-list li");

    for(var i =0; i < tabs.length; i++){
        tabs[i].addEventListener("click", switchTab);

        function switchTab(event){

            event.preventDefault();

            document.querySelector(".settings-tabs-list li.active").classList.remove("active");
            document.querySelector(".settings-tab-content .tab-pane.active").classList.remove("active");

            var clickedTab = event.currentTarget;
            var anchor = event.target;
            var activePaneID = anchor.getAttribute("href");

            clickedTab.classList.add("active");
            document.querySelector(activePaneID).classList.add("active");
        }
    }

  

});



//  // Add JavaScript for Ajax confirmation

//  document.addEventListener("DOMContentLoaded", function() {

//  // Edit
   

//    // Get the buttons that opens the modal
//    var btn = document.querySelectorAll(".updateButton");

//    for (let i = 0; i < btn.length; i++) {
//     btn[i].addEventListener('click', function() {

//           // Get the modal
//    var modal = document.getElementById("myModal");

 
//    // When the user clicks on the button, open the modal
   
//    modal.style.display = "block";
   
//   // Get the <span> element that closes the modal
//   var span = document.getElementsByClassName("close")[0];
 
//    // When the user clicks on <span> (x), close the modal
//    span.onclick = function() {
//    modal.style.display = "none";
//    }
   

  
//      if (modal) {

//       // Send an Ajax request to handle the update
//       var xhr = new XMLHttpRequest();
//       var formData = new FormData(document.getElementById("updateForm"));
//       xhr.open("POST", "admin.php?page=modify", true);
//       xhr.onreadystatechange = function() {
//         if (xhr.readyState == 4 && xhr.status == 200) {
//           // Handle success if needed
//           console.log(xhr.responseText);
//         }
//       };
//       xhr.send(formData);
       
//      }

//     });
//   }
  

//  });

 
