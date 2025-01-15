<?php
/**
 * Donation History Page Template
 * 
 * @package GusviraDigital
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Riwayat Donasi', 'gusviradigital'); ?></h1>
    <hr class="wp-header-end">

    <!-- Filters -->
    <div class="tablenav top">
        <div class="alignleft actions">
            <!-- Program Filter -->
            <select name="program_id" id="program-filter">
                <option value=""><?php esc_html_e('Semua Program', 'gusviradigital'); ?></option>
                <?php
                $programs = get_posts([
                    'post_type' => 'program',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                ]);

                foreach ($programs as $program) {
                    printf(
                        '<option value="%d">%s</option>',
                        $program->ID,
                        esc_html($program->post_title)
                    );
                }
                ?>
            </select>

            <!-- Status Filter -->
            <select name="status" id="status-filter">
                <option value=""><?php esc_html_e('Semua Status', 'gusviradigital'); ?></option>
                <option value="pending"><?php esc_html_e('Menunggu Pembayaran', 'gusviradigital'); ?></option>
                <option value="processing"><?php esc_html_e('Sedang Diproses', 'gusviradigital'); ?></option>
                <option value="completed"><?php esc_html_e('Berhasil', 'gusviradigital'); ?></option>
                <option value="failed"><?php esc_html_e('Gagal', 'gusviradigital'); ?></option>
                <option value="refunded"><?php esc_html_e('Dikembalikan', 'gusviradigital'); ?></option>
                <option value="cancelled"><?php esc_html_e('Dibatalkan', 'gusviradigital'); ?></option>
            </select>

            <!-- Date Filter -->
            <input type="date" name="start_date" id="start-date" placeholder="<?php esc_attr_e('Tanggal Mulai', 'gusviradigital'); ?>">
            <input type="date" name="end_date" id="end-date" placeholder="<?php esc_attr_e('Tanggal Akhir', 'gusviradigital'); ?>">

            <button type="button" class="button" id="filter-button">
                <?php esc_html_e('Filter', 'gusviradigital'); ?>
            </button>
        </div>

        <div class="alignleft actions">
            <button type="button" class="button" id="export-button">
                <?php esc_html_e('Export CSV', 'gusviradigital'); ?>
            </button>
        </div>

        <!-- Search Box -->
        <div class="tablenav-pages">
            <div class="search-box">
                <input type="search" id="donation-search" name="s" value="">
                <button type="button" class="button" id="search-button">
                    <?php esc_html_e('Cari', 'gusviradigital'); ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Summary -->
    <div class="donation-summary">
        <div class="summary-item">
            <h3><?php esc_html_e('Total Donasi', 'gusviradigital'); ?></h3>
            <p class="total-donations"><?php echo esc_html($total); ?></p>
        </div>
        <div class="summary-item">
            <h3><?php esc_html_e('Total Terkumpul', 'gusviradigital'); ?></h3>
            <p class="total-amount"><?php echo gdp_format_rupiah($total_amount); ?></p>
        </div>
    </div>

    <!-- Donations Table -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php esc_html_e('ID', 'gusviradigital'); ?></th>
                <th><?php esc_html_e('Program', 'gusviradigital'); ?></th>
                <th><?php esc_html_e('Nama', 'gusviradigital'); ?></th>
                <th><?php esc_html_e('Email', 'gusviradigital'); ?></th>
                <th><?php esc_html_e('No. WhatsApp', 'gusviradigital'); ?></th>
                <th><?php esc_html_e('Jumlah', 'gusviradigital'); ?></th>
                <th><?php esc_html_e('Status', 'gusviradigital'); ?></th>
                <th><?php esc_html_e('Metode Pembayaran', 'gusviradigital'); ?></th>
                <th><?php esc_html_e('Tanggal', 'gusviradigital'); ?></th>
            </tr>
        </thead>
        <tbody id="the-list">
            <?php if ($donations): ?>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?php echo esc_html($donation->id); ?></td>
                        <td><?php echo esc_html($donation->program_name); ?></td>
                        <td>
                            <?php echo $donation->is_anonymous 
                                ? esc_html__('Hamba Allah', 'gusviradigital')
                                : esc_html($donation->name); ?>
                        </td>
                        <td><?php echo esc_html($donation->email); ?></td>
                        <td><?php echo esc_html($donation->phone); ?></td>
                        <td><?php echo gdp_format_rupiah($donation->amount); ?></td>
                        <td>
                            <span class="donation-status status-<?php echo esc_attr($donation->payment_status); ?>">
                                <?php echo gdp_get_donation_status_label($donation->payment_status); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html($donation->payment_method); ?></td>
                        <td><?php echo esc_html($donation->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="no-items">
                        <?php esc_html_e('Tidak ada donasi ditemukan.', 'gusviradigital'); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="tablenav bottom">
        <div class="tablenav-pages">
            <span class="displaying-num">
                <?php printf(
                    /* translators: %s: number of items */
                    esc_html__('%s item', 'gusviradigital'),
                    number_format_i18n($total)
                ); ?>
            </span>
            <span class="pagination-links">
                <button type="button" class="button prev-page" <?php echo $offset <= 0 ? 'disabled' : ''; ?>>
                    <span class="screen-reader-text"><?php esc_html_e('Previous page', 'gusviradigital'); ?></span>
                    <span aria-hidden="true">‹</span>
                </button>
                <span class="paging-input">
                    <label for="current-page-selector" class="screen-reader-text">
                        <?php esc_html_e('Current Page', 'gusviradigital'); ?>
                    </label>
                    <input class="current-page" id="current-page-selector" type="text" name="paged" 
                           value="<?php echo floor($offset / 10) + 1; ?>" size="1" aria-describedby="table-paging">
                    <span class="tablenav-paging-text">
                        <?php printf(
                            /* translators: 1: current page 2: total pages */
                            esc_html__('of %s', 'gusviradigital'),
                            '<span class="total-pages">' . ceil($total / 10) . '</span>'
                        ); ?>
                    </span>
                </span>
                <button type="button" class="button next-page" <?php echo ($offset + 10) >= $total ? 'disabled' : ''; ?>>
                    <span class="screen-reader-text"><?php esc_html_e('Next page', 'gusviradigital'); ?></span>
                    <span aria-hidden="true">›</span>
                </button>
            </span>
        </div>
    </div>
</div>

<style>
.donation-summary {
    display: flex;
    gap: 2rem;
    margin: 1rem 0;
    padding: 1rem;
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.summary-item h3 {
    margin: 0 0 0.5rem;
    font-size: 14px;
    color: #1d2327;
}

.summary-item p {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
    color: #2271b1;
}

.donation-status {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 3px;
    font-size: 12px;
    font-weight: 500;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-processing {
    background: #cce5ff;
    color: #004085;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.status-failed {
    background: #f8d7da;
    color: #721c24;
}

.status-refunded,
.status-cancelled {
    background: #e2e3e5;
    color: #383d41;
}
</style>

<script>
jQuery(document).ready(function($) {
    // Variables
    var limit = 10;
    var offset = 0;
    var total = <?php echo esc_js($total); ?>;

    // Load donations
    function loadDonations() {
        var data = {
            action: 'gdp_get_donation_history',
            nonce: '<?php echo wp_create_nonce('gdp_admin_nonce'); ?>',
            limit: limit,
            offset: offset,
            program_id: $('#program-filter').val(),
            status: $('#status-filter').val(),
            search: $('#donation-search').val(),
            start_date: $('#start-date').val(),
            end_date: $('#end-date').val(),
        };

        $.post(ajaxurl, data, function(response) {
            if (response.success) {
                // Update table
                var html = '';
                if (response.data.donations.length > 0) {
                    response.data.donations.forEach(function(donation) {
                        html += '<tr>';
                        html += '<td>' + donation.id + '</td>';
                        html += '<td>' + donation.program_name + '</td>';
                        html += '<td>' + (donation.is_anonymous ? '<?php esc_html_e('Hamba Allah', 'gusviradigital'); ?>' : donation.name) + '</td>';
                        html += '<td>' + donation.email + '</td>';
                        html += '<td>' + donation.phone + '</td>';
                        html += '<td>' + formatRupiah(donation.amount) + '</td>';
                        html += '<td><span class="donation-status status-' + donation.payment_status + '">' + getDonationStatusLabel(donation.payment_status) + '</span></td>';
                        html += '<td>' + donation.payment_method + '</td>';
                        html += '<td>' + donation.created_at + '</td>';
                        html += '</tr>';
                    });
                } else {
                    html = '<tr><td colspan="9" class="no-items"><?php esc_html_e('Tidak ada donasi ditemukan.', 'gusviradigital'); ?></td></tr>';
                }
                $('#the-list').html(html);

                // Update summary
                $('.total-donations').text(response.data.total);
                $('.total-amount').text(formatRupiah(response.data.total_amount));

                // Update pagination
                total = response.data.total;
                updatePagination();
            }
        });
    }

    // Handle filter button click
    $('#filter-button').on('click', function() {
        offset = 0;
        loadDonations();
    });

    // Handle search button click
    $('#search-button').on('click', function() {
        offset = 0;
        loadDonations();
    });

    // Handle export button click
    $('#export-button').on('click', function() {
        var data = {
            action: 'gdp_export_donation_history',
            nonce: '<?php echo wp_create_nonce('gdp_admin_nonce'); ?>',
            program_id: $('#program-filter').val(),
            status: $('#status-filter').val(),
            search: $('#donation-search').val(),
            start_date: $('#start-date').val(),
            end_date: $('#end-date').val(),
        };

        // Create form and submit
        var $form = $('<form>', {
            method: 'post',
            action: ajaxurl,
        });

        // Add form fields
        Object.keys(data).forEach(function(key) {
            $form.append($('<input>', {
                type: 'hidden',
                name: key,
                value: data[key],
            }));
        });

        // Submit form
        $('body').append($form);
        $form.submit();
        $form.remove();
    });

    // Handle pagination
    $('.prev-page').on('click', function() {
        if (offset - limit >= 0) {
            offset -= limit;
            loadDonations();
        }
    });

    $('.next-page').on('click', function() {
        if (offset + limit < total) {
            offset += limit;
            loadDonations();
        }
    });

    $('#current-page-selector').on('change', function() {
        var page = parseInt($(this).val());
        if (page > 0 && page <= Math.ceil(total / limit)) {
            offset = (page - 1) * limit;
            loadDonations();
        }
    });

    // Update pagination
    function updatePagination() {
        var currentPage = Math.floor(offset / limit) + 1;
        var totalPages = Math.ceil(total / limit);

        $('#current-page-selector').val(currentPage);
        $('.total-pages').text(totalPages);
        $('.prev-page').prop('disabled', offset <= 0);
        $('.next-page').prop('disabled', offset + limit >= total);
        $('.displaying-num').text(total + ' item');
    }

    // Format rupiah
    function formatRupiah(amount) {
        return 'Rp ' + parseInt(amount).toLocaleString('id-ID');
    }

    // Get donation status label
    function getDonationStatusLabel(status) {
        var labels = {
            'pending': '<?php esc_html_e('Menunggu Pembayaran', 'gusviradigital'); ?>',
            'processing': '<?php esc_html_e('Sedang Diproses', 'gusviradigital'); ?>',
            'completed': '<?php esc_html_e('Berhasil', 'gusviradigital'); ?>',
            'failed': '<?php esc_html_e('Gagal', 'gusviradigital'); ?>',
            'refunded': '<?php esc_html_e('Dikembalikan', 'gusviradigital'); ?>',
            'cancelled': '<?php esc_html_e('Dibatalkan', 'gusviradigital'); ?>',
        };
        return labels[status] || status;
    }
});
</script> 