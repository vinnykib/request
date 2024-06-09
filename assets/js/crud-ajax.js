document.addEventListener("DOMContentLoaded", function () {
  //Approve
  // Get the list of approve buttons by class name
  let approveItems = document.querySelectorAll(".approveButton");

  // Add event listeners for approve buttons
  approveItems.forEach((approveButton) => {
    approveButton.addEventListener("click", function (event) {
      event.preventDefault();
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
});
