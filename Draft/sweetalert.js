   $('.swalfire').on('click', function () {
     Swal.fire({
      title: "Delete File?", // Main popup title
      text: "Are you sure you want to delete this file permanently?", // Message under title
      icon: "warning", // Icon: warning, info, success, etc.

      showCancelButton: true, // Show the cancel button
      confirmButtonText: "Yes, delete it!", // Confirm button text
      cancelButtonText: "No, cancel",       // Cancel button text

      confirmButtonColor: "#d33",  // Confirm button color (red)
      cancelButtonColor: "#3085d6", // Cancel button color (blue)
      reverseButtons: true,        // Cancel appears on left, confirm on right

      background: "#f0f0f0",       // Light grey background

      // Animate when shown and hidden
      showClass: {
        popup: "animate__animated animate__fadeInDown"
      },
      hideClass: {
        popup: "animate__animated animate__fadeOutUp"
      }
    }).then((result) => {
      // Check user's choice
      if (result.isConfirmed) {
        Swal.fire("Deleted!", "Your file has been deleted.", "success");
        
        // Optionally call your delete function here
        // deleteItem();

      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire("Cancelled", "Your file is safe :)", "error");
      }
    });
  });