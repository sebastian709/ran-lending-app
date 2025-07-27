// Function to update total and breakdown
function updateTotal() {
    let total = 0;
    let breakdownHTML = '';

    // Collect checked normal payments (Next Payment + Advance)
    $('.payment-option input[type="checkbox"]:checked').each(function () {
        const amount = parseFloat($(this).data('amount'));
        const label = $(this).closest('.list-group-item').find('.fw-semibold').text().trim();

        total += amount;
        breakdownHTML += `
            <li class="d-flex justify-content-between">
                <span>${label}</span>
                <span>₱${amount.toLocaleString()}</span>
            </li>`;
    });

    // Collect checked partial payments
    $('.partial-option input[type="checkbox"]:checked').each(function () {
        const amount = parseFloat($(this).data('amount'));
        const category = $(this).data('category');

        total += amount;
        breakdownHTML += `
            <li class="d-flex justify-content-between">
                <span>${category}</span>
                <span>₱${amount.toLocaleString()}</span>
            </li>`;
    });

    // Update Total Amount Display
    $('#totalAmount').text('₱' + total.toLocaleString());

    // Update Breakdown Section
    if (breakdownHTML === '') {
        $('#breakdownBody').html('<li class="text-center text-muted">No data available</li>');
    } else {
        $('#breakdownBody').html(breakdownHTML +
            `<li class="d-flex justify-content-between fw-bold border-top mt-2 pt-2 text-muted">
                <span>Total</span>
                <span>₱${total.toLocaleString()}</span>
            </li>`);
    }
}

// Handle advance/next payment checkbox changes
$(document).on('change', '.payment-option input[type="checkbox"]', function () {
    const index = $('.payment-option input[type="checkbox"]').index(this);

    if ($(this).is(':checked')) {
        // Auto-check previous months if one is checked
        $('.payment-option input[type="checkbox"]').slice(0, index + 1).prop('checked', true);
    } else {
        // Uncheck future months if unchecked
        $('.payment-option input[type="checkbox"]').slice(index + 1).prop('checked', false);
    }

    updateTotal();
});

// Handle Payment Type dropdown (Monthly or Full)
$(document).on('change', '#payAllCheck', function () {
     if ($(this).is(':checked')) {
        $('.payment-option input[type="checkbox"]').prop('checked', true);
    } else {
        $('.payment-option input[type="checkbox"]').prop('checked', false);
        $('.due_payment').prop('checked', true);
    }

    // Reset partial section if open
    $('.partial-option input[type="checkbox"]').prop('checked', false);
    $('.partial_section').addClass('d-none');
    $('.advance_section').removeClass('d-none');

    updateTotal();
});

// Toggle Advance Payments section
$('#toggleAdvance').on('click', function () {
    $('.advance-months').slideToggle();
    $(this).find('i').toggleClass('bi-chevron-down bi-chevron-up');
});

// Show Partial Payment Section
$(document).on('click', '.pay-partial-btn', function () {
    $('.partial_section').removeClass('d-none'); // Show Partial
    $('.advance_section').addClass('d-none'); // Hide Advance

    // Uncheck all regular payment options
    $('.due_payment').prop('checked', false);
    $('.payment-option input[type="checkbox"]').prop('checked', false);

    updateTotal();
});

// Close Partial Payment Section
$(document).on('click', '.close-partial', function () {
    $('.partial_section').addClass('d-none');
    $('.advance_section').removeClass('d-none');

    // Reset partial selections
    $('.partial-option input[type="checkbox"]').prop('checked', false);

    // Restore original next payment
    $('.due_payment').prop('checked', true);

    updateTotal();
});

// Handle Partial Payment checkbox changes
$(document).on('change', '.partial-option input[type="checkbox"]', function () {
    updateTotal();
});

// Initial total update on page load
$(document).ready(function () {
    updateTotal();
});

