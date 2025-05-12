<!-- resources/views/one-time-payment.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>One-Time Donation</title>

    <!-- Bootstrap for Styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script> <!-- Stripe.js -->
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4 text-center">One-Time Donation</h1>

    <!-- One-Time Donation Form -->
    <div class="p-4 shadow-lg card">
        <div class="card-body">
            <form id="fundraiser-form">
                <div class="row g-3" id="fundraiser">
                    <!-- Fundraiser Name -->
                    <div class="col-md-6">
                        <label for="fundraisername" class="form-label">Fundraiser Name</label>
                        <input class="form-control" type="text" name="fundraisername" id="fundraisername" placeholder="Enter your name" required>
                    </div>
                    <!-- Fundraiser Email -->
                    <div class="col-md-6">
                        <label for="fundraiseremail" class="form-label">Fundraiser Email</label>
                        <input class="form-control" type="email" name="fundraiseremail" id="fundraiseremail" placeholder="Enter your email" required>
                    </div>

                    <!-- Fundraiser Country and Contact Number -->
                    <div class="col-md-6">
                        <label for="country_selector" class="form-label">Country</label>
                        <input class="form-control" id="country_selector" type="text" placeholder="Select a country" required>
                    </div>
                    <div class="col-md-6">
                        <label for="fundraisercontact_number" class="form-label">Contact Number</label>
                        <input class="form-control" type="tel" name="fundraisercontact_number" id="fundraisercontact_number" placeholder="Enter your contact number" required>
                    </div>

                    <!-- Donation Amount and Tip Percentage -->
                    <div class="col-md-6">
                        <label for="amount" class="form-label">Donation Amount</label>
                        <div class="input-group">
                            <span class="input-group-text" id="currency">$</span>
                            <input type="number" class="form-control" name="amount" id="amount" placeholder="Enter amount" required onblur="calculateTip()" onkeypress="return allowOnlyNumbers(event)">
                            <input type="hidden" name="currency" id="currency" value="usd">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="tipsinpercentage" class="form-label">Tip Percentage</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="tipsinpercentage" id="tipsinpercentage" placeholder="Enter tip percentage" value="16" min="0" max="100" onblur="calculateTip()" maxlength="3" onkeypress="return allowOnlyNumbers(event)">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>

                    <!-- Tips Display (Read-only) -->
                    <div class="col-md-6">
                        <label for="tipsgiven" class="form-label">Tip Amount</label>
                        <input class="form-control" type="text" name="tipsgiven" id="tipsgiven" placeholder="Calculated tip" readonly>
                    </div>
                </div>

                <!-- Stripe Payment Form -->
                <div class="mt-4">
                    <label for="card-element" class="form-label">Card Details</label>
                    <div class="p-3 card border-light" id="card-element">
                        <!-- Stripe Elements will be inserted here -->
                    </div>
                </div>

                <!-- Stripe Payment Button -->
                <div class="mt-4 d-grid">
                    <button id="one-time-pay-button" type="button" class="btn btn-primary">Donate Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const one_time_donation = "http://127.0.0.1:8000/api/v1/stripe_payment";
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');

    // Mount the Stripe card element immediately when the page loads
    cardElement.mount('#card-element');

    // CSRF Token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Handle One-Time Donation
    document.getElementById('one-time-pay-button').addEventListener('click', async function() {
         const oneTimeDonationUrl = one_time_donation;
        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            alert(error.message);
        } else {
            const formData = new FormData(document.getElementById('fundraiser-form'));
            formData.append('payment_method', paymentMethod.id);
            ////alert(oneTimeDonationUrl);
            fetch(oneTimeDonationUrl, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': csrfToken},
                body: formData
            })
                .then(response => {
                    if (!response.ok) {
                        console.error('HTTP error:', response.status);
                        return response.json().then(errorData => {
                            console.error('Error details:', errorData);
                            throw new Error('Request failed');
                        });
                    }
                    //SaveDonation();
                    return response.json();
                })
                .then(data => {
                    alert(data.message || data.error);
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                });
            }

    });

    // Function to calculate tips (placeholder)
    function calculateTip() {
          const donationAmount = document.getElementById('amount').value;
        const tipsPercentage = document.getElementById('tipsinpercentage').value;
        const tipsGiven = (donationAmount * tipsPercentage / 100).toFixed(2);
        document.getElementById('tipsgiven').value = tipsGiven;
    }

    // Function to allow only numbers in certain inputs (placeholder)
    function allowOnlyNumbers(event) {
        return /\d/.test(event.key) || event.key === 'Backspace';
    }
</script>
</body>
</html>
