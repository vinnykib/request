document.addEventListener("DOMContentLoaded", function() {

    //Approve
  
    // Get the list of approve buttons by class name
    let approveItems = document.querySelectorAll(".approveButton");
  
    // Add event listeners for approve buttons
    approveItems.forEach(approveButton => {
      approveButton.addEventListener("click", function() {
        let shouldApprove = confirm("Are you sure you want to approve this request?");
        if (shouldApprove) {
  
          let approveForm = document.getElementById("approveForm");
  
          let approveUrl = approveForm.dataset.url;
          console.log(approveUrl);
  
          // Send a fetch request to handle approval            
          let approveParams = new URLSearchParams(new FormData(approveForm));
  
          fetch(approveUrl, {
            method: "POST",
            body: approveParams
          }).then(res => res.json())
            .catch(error => {
              console.error("Error:", error);
            })
            .then(response => {
              if (response === 0 || response === "error") {
                console.error("Error: Invalid response");
                return;
              }
              // Handle successful approval here
              console.log("Approve successful:", response);
            });            
        }
      });
    });

        //cancel

        // Get the list of cancel buttons by class name
        let cancelItems = document.querySelectorAll(".cancelButton");
        // Add event listeners for cancel buttons
        cancelItems.forEach(cancelButton => {
          cancelButton.addEventListener("click", function() {
            let cancelId = cancelButton.dataset.url;
            console.log(cancelId);
      
            let shouldCancel = confirm("Are you sure you want to cancel this request?");
            if (shouldCancel) {
              
              let cancelForm = document.getElementById("cancelForm");
      
              let cancelUrl = cancelForm.dataset.url;
              console.log(cancelUrl);
      
              let params = new URLSearchParams(new FormData(cancelForm));
      
              fetch(cancelUrl, {
                method: "POST",
                body: params
              }).then(res => res.json())
                .catch(error => {
                  console.error("Error:", error);
                })
                .then(response => {
                  if (response === 0 || response === "error") {
                    console.error("Error: Invalid response");
                    return;
                  }
                  // Handle successful cancellation here
                  console.log("Cancel successful:", response);
                });            
            }
          });
        });

  });
  