<?php
session_start();
require 'config/db.php';

$id = $_GET['id'] ?? null;
$token = $_GET['csrf_token'] ?? null;

// Basic checks
if (!isset($_SESSION['userID'], $id, $token) || !hash_equals($_SESSION['csrf_token'], $token)) {
    die("Unauthorized or invalid request.");
}

$id = filter_var($id, FILTER_VALIDATE_INT);
if (!$id) die("Invalid medicine ID.");

// Delete query
$stmt = $conn->prepare("DELETE FROM medicines WHERE medicine_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Redirect
header("Location: dashboard.php?deleted=1");
exit();
