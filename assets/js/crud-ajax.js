document.addEventListener("DOMContentLoaded", function (e) {
  //Approve
  // Get the list of approve buttons by class name
  let approveItems = document.querySelectorAll(".approveButton");

  // Add event listeners for approve buttons
  approveItems.forEach((approveButton) => {
    approveButton.addEventListener("click", function (event) {
      event.preventDefault();

      let approveId = approveButton.dataset.postid;
      console.log(approveId);

      let approveValue = document.getElementById("approve-id");
      approveValue.value = approveId;

      let shouldApprove = confirm(
        "Are you sure you want to approve this request?"
      );
      if (shouldApprove) {
        let approveForm = document.getElementById("approveForm");

        let approveUrl = approveForm.dataset.url;
        console.log(approveUrl);


        // Send a fetch request to handle approval
        let approveParams = new URLSearchParams(new FormData(approveForm));

        fetch(approveUrl, {
          method: "POST",
          body: approveParams,
        })
          .then((res) => res.json())
          .catch((error) => {
            console.error("Error:", error);
          })
          .then((response) => {
            if (response === 0 || response === "error") {
              console.error("Error: Invalid response");
              return;
            }
            // Handle successful approval here
            location.reload();
            console.log("Approve successful:", response);
          });
      }
    });
  });

  //cancel
  // Get the list of cancel buttons by class name
  let cancelItems = document.querySelectorAll(".cancelButton");
  // Add event listeners for cancel buttons
  cancelItems.forEach((cancelButton) => {
    cancelButton.addEventListener("click", function (event) {
      event.preventDefault();
      let cancelId = cancelButton.dataset.postid;
      console.log(cancelId);

      let cancelValue = document.getElementById("cancel-id");
      cancelValue.value = cancelId;

      let shouldCancel = confirm(
        "Are you sure you want to cancel this request?"
      );
      if (shouldCancel) {
        let cancelForm = document.getElementById("cancelForm");
        let cancelUrl = cancelForm.dataset.url;
        console.log(cancelUrl);

        let params = new URLSearchParams(new FormData(cancelForm));

        fetch(cancelUrl, {
          method: "POST",
          body: params,
        })
          .then((res) => res.json())
          .catch((error) => {
            console.error("Error:", error);
          })
          .then((response) => {
            if (response === 0 || response === "error") {
              console.error("Error: Invalid response");
              return;
            }
            // Handle successful cancellation here
            location.reload();
            console.log("Cancel successful:", response);
          });
      }
    });
  });

// Update
let updateItems = document.querySelectorAll(".updateButton");

// Step 3: Add event listeners for update buttons
for (let i = 0; i < updateItems.length; i++) {
    updateItems[i].addEventListener("click", function () {
        let updateId = this.dataset.postid; // Use 'this' to refer to the current button
        console.log("Update ID:", updateId);

        let updateValue = document.getElementById("update-id");
        updateValue.value = updateId;

        // Implement your update logic here, such as showing a form or making an Ajax request to update the data
        // Get the modal
        let updateModal = document.getElementById("update-modal");

        // When the user clicks on the edit button, open the modal
        updateModal.style.display = "block";

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            updateModal.style.display = "none";
        };

        // Optionally, you can populate the form with existing data here using updateId
    });
}

// Ensure that the form submission handler is added only once
    //Get update form
let updateForm = document.getElementById("update-form");
if (updateForm) {
updateForm.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission

    let updateUrl = updateForm.dataset.url;
    console.log("Update URL:", updateUrl);

    let updateParams = new URLSearchParams(new FormData(updateForm));

    fetch(updateUrl, {
        method: "POST",
        body: updateParams,
    })
    .then((res) => res.json())
    .then((response) => {
        if (response === 0 || response === "error") {
            console.error("Error: Invalid response");
            return;
        }
        // Handle successful update here
        location.reload();
        console.log("Update successful:", response);

        // Optionally close the modal upon successful update
        document.getElementById("update-modal").style.display = "none";
    })
    .catch((error) => {
        console.error("Error:", error);
    });
});
}

//Populate items to edit form
 // Get all the "Edit" buttons in the table
 const editButtons = document.querySelectorAll(".updateButton");

 // Add a click event listener to each "Edit" button
 editButtons.forEach((button) => {
   button.addEventListener("click", function () {
     // Find the row that contains the clicked button
     const row = button.closest("tr");

     // Get the data from the row
     const name = row.querySelector("td:nth-child(1)").textContent;
     const email = row.querySelector("td:nth-child(2)").textContent;
     const dateStr = row.querySelector("td:nth-child(3)").textContent;
    

     const phoneEdit = row.querySelector('.hidden-content-phone-edit').dataset.hiddencontent;
     const descriptionEdit = row.querySelector('.hidden-content-description-edit').dataset.hiddencontent;

     // Parse the date string
     let date = new Date(dateStr);
     // Get the components (month, day, year)
     let month = date.getMonth() + 1; // Months are zero-indexed
     let day = date.getDate();
     let year = date.getFullYear();

     // Format the components to mm-dd-yyyy
     let formattedDates = `${year}-${month.toString().padStart(2, "0")}-${day
       .toString()
       .padStart(2, "0")}`;

     console.log(formattedDates); // Output: 04-18-2024

     const startTime = row.querySelector(
       "td:nth-child(4) span:nth-child(1)"
     ).textContent;
     const endTime = row.querySelector(
       "td:nth-child(4) span:nth-child(2)"
     ).textContent;
     let status = row.querySelector("td:nth-child(5)").textContent;

     // Get the form you want to populate (e.g., using its ID)
     const form = document.getElementById("update-form");

     // Populate the form inputs with the data from the row
     form.querySelector('input[name="rqt-upd-name"]').value = name.trim();
     form.querySelector('input[name="rqt-upd-email"]').value = email.trim();
     form.querySelector('input[name="rqt-upd-phone"]').value = phoneEdit.trim();
     form.querySelector('input[name="rqt-upd-request-date"]').value = formattedDates.trim();
     form.querySelector('input[name="rqt-upd-start-time"]').value = startTime.trim();
     form.querySelector('input[name="rqt-upd-end-time"]').value = endTime.trim();
     form.querySelector('textarea[name="rqt-upd-description"]').value = descriptionEdit.trim();
     form.querySelector('select[name="rqt-upd-status"]').value = status.trim();

     


   });
 });


  // Delete
  // Step 1: Get the list of elements by class name
  let deleteItems = document.querySelectorAll(".deleteButton");

  for (let i = 0; i < deleteItems.length; i++) {
    deleteItems[i].addEventListener("click", function (event) {
      event.preventDefault();
      let deleteId = this.dataset.postid;
      console.log(deleteId);

      let deleteValue = document.getElementById("delete-id");
      deleteValue.value = deleteId;

      var shouldDelete = confirm(
        "Are you sure you want to delete this request?"
      );
      if (shouldDelete) {
        // If confirmed, send an Ajax request to handle deletion

        // Get the delete form and URL
        let deleteForm = document.getElementById("deleteForm"); // Corrected ID from "deletelForm"
        let deleteUrl = deleteForm.dataset.url;

        // Create URLSearchParams from the form data
        let deleteParams = new URLSearchParams(new FormData(deleteForm));

        // Send the fetch request
        fetch(deleteUrl, {
          method: "POST",
          body: deleteParams,
        })
          .then((response) => response.json())
          .then((response) => {
            if (response === 0 || response === "error") {
              console.error("Error: Invalid response");
              return;
            }
            // Handle successful deletion here
            location.reload();
            console.log("Delete successful:", response);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      }
    });
  }



  /**
   * 
   * 
   * 
   * Start of view modal
   * 
   * 
  */

  //Pending
let viewPendingItem = document.querySelectorAll(".pendingViewButton");

// Step 3: Add event listeners for view buttons
for (let i = 0; i < viewPendingItem.length; i++) {
  viewPendingItem[i].addEventListener("click", function () {
        let pendingViewId = this.dataset.postid; // Use 'this' to refer to the current button
        console.log("View ID:", pendingViewId);

        // Get the modal
        let pendingViewModal = document.getElementById("pending-view-modal");

        // When the user clicks on the view button, open the modal
        pendingViewModal.style.display = "block";

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
          pendingViewModal.style.display = "none";
        };
        // When the user clicks outside the modal, close the modal
        // pendingViewModal.onclick = function () {
        //   pendingViewModal.style.display = "none";
        // };
    });
}


 // Get all the "view" buttons in the table
 const viewPendingButtons = document.querySelectorAll(".pendingViewButton");

 // Add a click event listener to each "view" button
 viewPendingButtons.forEach((button) => {
   button.addEventListener("click", function () {
     // Find the row that contains the clicked button
     const row = button.closest("tr");

     // Get the data from the row
     const name = row.querySelector("td:nth-child(1)").textContent;
     const email = row.querySelector("td:nth-child(2)").textContent;
     const dateStr = row.querySelector("td:nth-child(3)").textContent;

     const phone = row.querySelector('.hidden-content-phone').dataset.hiddencontent;
     const description = row.querySelector('.hidden-content-description').dataset.hiddencontent;

     const startTime = row.querySelector(
       "td:nth-child(4) span:nth-child(1)"
     ).textContent;
     const endTime = row.querySelector(
       "td:nth-child(4) span:nth-child(2)"
     ).textContent;
     let status = row.querySelector("td:nth-child(5)").textContent;

     // Get the form you want to populate (e.g., using its ID)
     const viewDiv = document.getElementById("pending-view-modal-content");

     // Populate the form inputs with the data from the row
     viewDiv.querySelector(".view-modal-name").textContent = name.trim();
     viewDiv.querySelector(".view-modal-email").textContent = email.trim();
     viewDiv.querySelector(".view-modal-phone").textContent = phone.trim();
     viewDiv.querySelector(".view-modal-date").textContent = dateStr.trim();
     viewDiv.querySelector(".view-modal-time").textContent = startTime.trim() + ' - ' + endTime.trim();
     viewDiv.querySelector(".view-modal-description").textContent = description.trim();
     viewDiv.querySelector(".view-modal-status").textContent = status.trim();
     

   });
 });

   /**
    * 
    * All request list
    * 
   */
let viewItems = document.querySelectorAll(".viewButton");

// Step 3: Add event listeners for view buttons
for (let i = 0; i < viewItems.length; i++) {
  viewItems[i].addEventListener("click", function () {
        let viewId = this.dataset.postid; // Use 'this' to refer to the current button
        console.log("View ID:", viewId);

        // Get the modal
        let viewModal = document.getElementById("view-modal");

        // When the user clicks on the view button, open the modal
        viewModal.style.display = "block";

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close")[1];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
          viewModal.style.display = "none";
        };

        // When the user clicks outside the modal, close the modal
        // viewModal.onclick = function () {
        //   viewModal.style.display = "none";
        // };
        
    });
}

 // Get all the "view" buttons in the table
 const viewButtons = document.querySelectorAll(".viewButton");

 // Add a click event listener to each "view" button
 viewButtons.forEach((button) => {
   button.addEventListener("click", function () {
     // Find the row that contains the clicked button
     const row = button.closest("tr");

     // Get the data from the row
     const name = row.querySelector("td:nth-child(1)").textContent;
     const email = row.querySelector("td:nth-child(2)").textContent;
     const dateStr = row.querySelector("td:nth-child(3)").textContent;
     const phone = row.querySelector('.hidden-content-phone-edit').dataset.hiddencontent;
     const description = row.querySelector('.hidden-content-description-edit').dataset.hiddencontent;

     const startTime = row.querySelector(
       "td:nth-child(4) span:nth-child(1)"
     ).textContent;
     const endTime = row.querySelector(
       "td:nth-child(4) span:nth-child(2)"
     ).textContent;
     let status = row.querySelector("td:nth-child(5)").textContent;

     // Get the form you want to populate (e.g., using its ID)
     const viewDiv = document.getElementById("view-modal-content");

     // Populate the form inputs with the data from the row
     viewDiv.querySelector(".view-modal-name").textContent = name.trim();
     viewDiv.querySelector(".view-modal-email").textContent = email.trim();
     viewDiv.querySelector(".view-modal-phone").textContent = phone.trim();
     viewDiv.querySelector(".view-modal-date").textContent = dateStr.trim();
     viewDiv.querySelector(".view-modal-time").textContent = startTime.trim() + ' - ' + endTime.trim();
     viewDiv.querySelector(".view-modal-description").textContent = description.trim();
     viewDiv.querySelector(".view-modal-status").textContent = status.trim();
     

   });
 });



  /***
   * 
   * 
   * 
   * End of view modal
   * 
   * 
   * 
   * 
  */

//Export
// Get the button by ID
let exportButton = document.getElementById("export-button");

if (exportButton) {
    exportButton.addEventListener("click", function (event) {
        event.preventDefault();

        // Ask for confirmation before proceeding with the export
        let shouldExport = confirm("Are you sure you want to export these requests?");
        if (!shouldExport) {
            return; // If the user cancels, do nothing
        }

        // Get the export form and URL
        let exportForm = document.getElementById("export-form");
        let exportUrl = exportForm.dataset.url;

        // Create URLSearchParams from the form data
        let exportParams = new URLSearchParams(new FormData(exportForm));

        // Send the fetch request
        fetch(exportUrl, {
            method: "POST",
            body: exportParams,
        })
        .then((response) => {
            // Check if the response is a CSV file
            if (response.headers.get('Content-Type').includes('text/csv')) {
                return response.blob(); // Get the CSV content as a Blob
            } else {
                return response.json(); // Otherwise, parse as JSON
            }
        })
        .then((data) => {
            if (data instanceof Blob) {
                // Handle CSV download
                let url = window.URL.createObjectURL(data);
                let a = document.createElement('a');
                a.href = url;
                a.download = 'requests_export.csv';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            } else if (data.error) {
                console.error("Error:", data.error);
            } else {
                console.log("Export successful:", data);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
        });
    });
}


});

