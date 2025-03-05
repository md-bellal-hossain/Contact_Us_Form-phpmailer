<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .loading-spinner {
            display: none;
        }
        .alert {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Contact Form</h3>
                    </div>
                    <div class="card-body">
                        <!-- Alert Messages -->
                        <div class="alert alert-success" id="successAlert" role="alert"></div>
                        <div class="alert alert-danger" id="errorAlert" role="alert"></div>

                        <form id="contactForm">
                            <div class="mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="spinner-border spinner-border-sm loading-spinner" role="status" aria-hidden="true"></span>
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#contactForm').on('submit', function(e) {
                e.preventDefault();
                
                // Hide previous alerts
                $('.alert').hide();
                
                // Show loading spinner
                $('.loading-spinner').show();
                $('#submitBtn').prop('disabled', true);
                
                $.ajax({
                    url: 'send.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#successAlert').html(response.message).show();
                            $('#contactForm')[0].reset();
                        } else {
                            $('#errorAlert').html(response.message).show();
                        }
                    },
                    error: function() {
                        $('#errorAlert').html('An error occurred. Please try again later.').show();
                    },
                    complete: function() {
                        // Hide loading spinner
                        $('.loading-spinner').hide();
                        $('#submitBtn').prop('disabled', false);
                    }
                });
            });
        });
    </script>
</body>
</html>