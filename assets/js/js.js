document.addEventListener("DOMContentLoaded",function(){
    let tabs = document.querySelectorAll(".settings-tabs-list li");

    for(let i =0; i < tabs.length; i++){
        tabs[i].addEventListener("click", switchTab);

        function switchTab(event){

            event.preventDefault();

            document.querySelector(".settings-tabs-list li.active").classList.remove("active");
            document.querySelector(".settings-tab-content .tab-pane.active").classList.remove("active");

            let clickedTab = event.currentTarget;
            let anchor = event.target;
            let activePaneID = anchor.getAttribute("href");

            clickedTab.classList.add("active");
            document.querySelector(activePaneID).classList.add("active");
        }
    }

});

jQuery(document).ready(function($) {
    $('.my-color-field').wpColorPicker();
    $('.ui-color').wpColorPicker();
});


