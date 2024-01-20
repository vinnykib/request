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

function confirmDelete() {
    // Display a confirmation dialog
    var result = confirm("Are you sure you want to delete this item?");
    
    // // Check the result of the confirmation dialog
    // if (result) {
    //     // User clicked OK, proceed with the delete operation
    //     alert("Item deleted!"); // Replace this with your actual delete logic
    // } else {
    //     // User clicked Cancel, do nothing or provide feedback
    //     alert("Delete operation canceled.");
    // }
}