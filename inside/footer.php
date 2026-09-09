 </div>
    </div>

    <!-- Fixed Footer -->
    <footer class="bg-primary text-light text-center py-3 fixed-bottom">
      <span>&copy; 2026 Open Class Portal</span>
    </footer>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
      integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
      integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha"
      crossorigin="anonymous"
    ></script>
    <script src="assets/dist/js/dashboard.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    // 1. Restore tab from hash
    if (window.location.hash) {
      const activeTab = document.querySelector(
        `a[data-bs-target="${window.location.hash}"]`
      );
      if (activeTab) {
        new bootstrap.Tab(activeTab).show();
      }
    }

    // 2. Update hash when switching tabs
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
      tab.addEventListener('shown.bs.tab', function (e) {
        const target = e.target.getAttribute('data-bs-target');
        history.replaceState(null, null, target);
      });
    });

    // 3. Create user via modal form
    const createForm = document.getElementById('createFormModal');
    if (createForm) {
      createForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(createForm);

        fetch('create_action.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.text())
          .then(result => {
            if (result.trim() === 'success') {
              const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
              modal.hide();
              createForm.reset();
              location.reload();
            } else {
              alert('Failed to add user: ' + result);
            }
          })
          .catch(error => {
            console.error('Create user error:', error);
            alert('An error occurred while adding the user.');
          });
      });
    }

    // 4. Create subject via modal form
    const createSubjectForm = document.getElementById('createSubjectFormModal');
    if (createSubjectForm) {
      createSubjectForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const subjectformData = new FormData(createSubjectForm);

        fetch('create_subject_action.php', {
          method: 'POST',
          body: subjectformData
        })
          .then(subjectresponse => subjectresponse.json())
          .then(data => {
            if (data.status === 'success') {
              const modal = bootstrap.Modal.getInstance(document.getElementById('addSubjectModal'));
              modal.hide();
              createSubjectForm.reset();
              location.reload();
            } else {
              alert(data.message || 'Failed to add subject.');
            }
          })
          .catch(error => {
            console.error('Create subject error:', error);
            alert('An error occurred while adding the subject.');
          });
      });
    }

    // 5. Edit user via modal form
    document.querySelectorAll('form[id^="editForm"]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        const formId = (typeof form.id === "string") ? form.id.replace(/^editForm/, '') : '';

        fetch('edit_action.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal' + formId));
              if (modal) modal.hide();
              location.reload();
            } else {
              alert(data.message || 'Failed to update user.');
            }
          })
          .catch(error => {
            console.error('Update user error:', error);
            alert('An error occurred while updating the user.');
          });
      });
    });

    // 6. Edit subject via modal form
    document.querySelectorAll('form[id^="editSubjectForm"]').forEach(function (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(form);
        const formId = (typeof form.id === "string") ? form.id.replace(/^editSubjectForm/, '') : '';

        fetch('edit_subject_action.php', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(data => {
            if (data.status === 'success') {
              const modal = bootstrap.Modal.getInstance(document.getElementById('editSubjectModal' + formId));
              if (modal) modal.hide();
              location.reload();
            } else {
              alert(data.message || 'Failed to update subject.');
            }
          })
          .catch(error => {
            console.error('Update subject error:', error);
            alert('An error occurred while updating the subject.');
          });
      });
    });




  // Search Subjects
  const searchSubjectBox = document.getElementById("searchSubjectBox");
  if (searchSubjectBox) {
    searchSubjectBox.addEventListener("keyup", function () {
      const filter = searchSubjectBox.value.toLowerCase();
      document.querySelectorAll("#subjectsTable tbody tr").forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });
  }

  // Search Students
  const searchStudentBox = document.getElementById("searchStudentBox");
  if (searchStudentBox) {
    searchStudentBox.addEventListener("keyup", function () {
      const filter = searchStudentBox.value.toLowerCase();
      document.querySelectorAll("#studentsTable tbody tr").forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });
  }


    // Search Resources
  const searchResourceBox = document.getElementById("searchResourceBox");
  if (searchResourceBox) {
    searchResourceBox.addEventListener("keyup", function () {
      const filter = searchResourceBox.value.toLowerCase();
      document.querySelectorAll("#resourcesTable tbody tr").forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });
  }


  //    // Search Images
  // const searchImageBox = document.getElementById("searchImageBox");
  // if (searchImageBox) {
  //   searchImageBox.addEventListener("keyup", function () {
  //     const filter = searchImageBox.value.toLowerCase();
  //     document.querySelectorAll("#imagesTable tbody tr").forEach(row => {
  //       const text = row.textContent.toLowerCase();
  //       row.style.display = text.includes(filter) ? "" : "none";
  //     });
  //   });
  // }


  

  

  });
</script>


<script>
  document.addEventListener("DOMContentLoaded", function () {
  // Create Resource
  const createResourceForm = document.getElementById("createResourceFormModal");
  if (createResourceForm) {
    createResourceForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(createResourceForm);

      fetch("create_resource_action.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            const modal = bootstrap.Modal.getInstance(document.getElementById("addResourceModal"));
            modal.hide();
            createResourceForm.reset();
            location.reload();
          } else {
            alert(data.message || "Failed to add resource.");
          }
        })
        .catch(error => {
          console.error("Create resource error:", error);
          alert("An error occurred while adding the resource.");
        });
    });
  }

  // Edit Resource
  document.querySelectorAll('form[id^="editResourceForm"]').forEach(function (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(form);
      const formId = (typeof form.id === "string") ? form.id.replace(/^editResourceForm/, "") : "";

      fetch("edit_resource_action.php", {
        method: "POST",
        body: formData
      })
        .then(response => response.json())
        .then(data => {
          if (data.status === "success") {
            const modal = bootstrap.Modal.getInstance(document.getElementById("editResourceModal" + formId));
            if (modal) modal.hide();
            location.reload();
          } else {
            alert(data.message || "Failed to update resource.");
          }
        })
        .catch(error => {
          console.error("Update resource error:", error);
          alert("An error occurred while updating the resource.");
        });
    });
  });


});

</script>






<script> 


document.addEventListener("DOMContentLoaded", function () {

   //    // Search Images
  const searchImageBox = document.getElementById("searchImageBox");
  if (searchImageBox) {
    searchImageBox.addEventListener("keyup", function () {
      const filter = searchImageBox.value.toLowerCase();
      document.querySelectorAll("#imagesTable tbody tr").forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });
  }

  const createImageForm = document.getElementById("createImageFormModal");
  if (createImageForm) {
    createImageForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(createImageForm);

      fetch("create_image_action.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(result => {
        if (result.status === "success") {
          const modal = bootstrap.Modal.getInstance(document.getElementById("addImageModal"));
          modal.hide();
          createImageForm.reset();
          location.reload(); // refresh to show new image
        } else {
          alert("Failed: " + result.message);
        }
      })
      .catch(error => {
        console.error("Upload error:", error);
        alert("An error occurred while uploading the image.");
      });
    });
  }
  document.querySelectorAll("[id^='editImageForm']").forEach(form => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(this);
      const imageId = formData.get("id");

      fetch("edit_image_action.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(result => {
        if (result.status === "success") {
          // Hide modal
          const modal = bootstrap.Modal.getInstance(document.getElementById("editImageModal" + imageId));
          if (modal) modal.hide();

          // Update row dynamically
          const row = document.getElementById("imageRow" + imageId);
          if (row) {
            // Update title
            row.querySelector("td:nth-child(3)").textContent = formData.get("title");
            // Update description
            row.querySelector("td:nth-child(4)").textContent = formData.get("description");

            // If new image uploaded, update thumbnail
            if (formData.get("image") && formData.get("image").name) {
              const imgCell = row.querySelector("td:nth-child(2) img");
              if (imgCell) {
                // Temporary preview until reload
                const newImgURL = URL.createObjectURL(formData.get("image"));
                imgCell.src = newImgURL;
              }
            }
          }

          // Optional: show success alert
          const alertBox = document.createElement("div");
          alertBox.className = "alert alert-success mt-2";
          alertBox.textContent = "Image updated successfully.";
          document.querySelector("#imagesTableContainer").prepend(alertBox);

          // Auto-hide alert after 3 seconds
          setTimeout(() => alertBox.remove(), 3000);
        } else {
          alert("Failed: " + result.message);
        }
      })
      .catch(error => {
        console.error("Update error:", error);
        alert("An error occurred while updating the image.");
      });
    });
  });
  document.querySelectorAll(".delete-image-btn").forEach(button => {
    button.addEventListener("click", function () {
      const imageId = this.getAttribute("data-id");

      const formData = new FormData();
      formData.append("id", imageId);

      fetch("delete_image_action.php", {
        method: "POST",
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === "success") {
          // Hide modal
          const modal = bootstrap.Modal.getInstance(document.getElementById("deleteImageModal" + imageId));
          if (modal) modal.hide();

          // Remove row from table dynamically
          const row = document.getElementById("imageRow" + imageId);
          if (row) {
            row.remove();
          }

          // Optional: show success alert
          const alertBox = document.createElement("div");
          alertBox.className = "alert alert-success mt-2";
          alertBox.textContent = "Image deleted successfully.";
          document.querySelector("#imagesTableContainer").prepend(alertBox);

          // Auto-hide alert after 3 seconds
          setTimeout(() => alertBox.remove(), 3000);
        } else {
          alert(data.message || "Failed to delete image.");
        }
      })
      .catch(error => {
        console.error("Delete image error:", error);
        alert("An error occurred while deleting the image.");
      });
    });
  });
});

</script>


<!-- Reactions tab AJAX -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const galleryImages = Array.from(document.querySelectorAll(".gallery-img"));
    const modalElement = document.getElementById("imageModal");
    if (!galleryImages.length || !modalElement) return;

    const modalImage = document.getElementById("modalImage");
    const likeCount = document.getElementById("likeCount");
    const dislikeCount = document.getElementById("dislikeCount");
    const reactionButtons = Array.from(document.querySelectorAll(".reaction-btn"));
    const imageModal = new bootstrap.Modal(modalElement);
    let currentIndex = 0;

    function updateReactionDisplay(image) {
      likeCount.textContent = image.dataset.likes || "0";
      dislikeCount.textContent = image.dataset.dislikes || "0";
      reactionButtons.forEach(function (button) {
        const selected = button.dataset.action === image.dataset.userReaction;
        button.classList.toggle("active", selected);
        button.setAttribute("aria-pressed", selected ? "true" : "false");
      });
    }

    function showImage(index) {
      const image = galleryImages[index];
      currentIndex = index;
      modalImage.src = image.src;
      modalImage.alt = image.alt;
      updateReactionDisplay(image);
      imageModal.show();
    }

    galleryImages.forEach(function (image, index) {
      image.addEventListener("click", function () { showImage(index); });
    });

    document.getElementById("prevImage").addEventListener("click", function () {
      showImage((currentIndex - 1 + galleryImages.length) % galleryImages.length);
    });
    document.getElementById("nextImage").addEventListener("click", function () {
      showImage((currentIndex + 1) % galleryImages.length);
    });

    document.addEventListener("keydown", function (event) {
      if (!modalElement.classList.contains("show")) return;
      if (event.key === "ArrowLeft") showImage((currentIndex - 1 + galleryImages.length) % galleryImages.length);
      if (event.key === "ArrowRight") showImage((currentIndex + 1) % galleryImages.length);
    });

    reactionButtons.forEach(function (button) {
      button.addEventListener("click", function () {
        const image = galleryImages[currentIndex];
        reactionButtons.forEach(function (item) { item.disabled = true; });

        fetch("reaction_action.php", {
          method: "POST",
          credentials: "same-origin",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: new URLSearchParams({ image_id: image.dataset.id, reaction: button.dataset.action })
        })
          .then(function (response) {
            return response.json().then(function (data) {
              if (!response.ok || data.status !== "success") throw new Error(data.message || "Reaction failed");
              return data;
            });
          })
          .then(function (data) {
            image.dataset.likes = data.total_likes;
            image.dataset.dislikes = data.total_dislikes;
            image.dataset.userReaction = data.user_reaction;
            updateReactionDisplay(image);
          })
          .catch(function (error) { console.error("Reaction error:", error); })
          .finally(function () { reactionButtons.forEach(function (item) { item.disabled = false; }); });
      });
    });
  });
</script>





  </body>
</html>



