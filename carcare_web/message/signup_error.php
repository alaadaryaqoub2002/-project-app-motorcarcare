<?php include '../conDB/head.php'; ?>
<div class="content-wrapper d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="container text-center" style="max-width: 600px; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
        <h1 style="color: red;">Sign Up Error</h1>
        <p style="color: #555;"><?php echo htmlspecialchars($_GET['message'] ?? 'An error occurred during sign up. Please try again.'); ?></p>
        <a href="../signup_form.php" class="btn btn-primary" style="padding: 10px 20px; border-radius: 5px; font-size: 16px;">Back to Sign Up</a>
    </div>
</div>
<?php include '../conDB/footer.php'; ?>
