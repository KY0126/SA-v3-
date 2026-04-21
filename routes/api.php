<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{UserController, ClubController, VenueController, ActivityController, EquipmentController, ReservationController, CrudController};

// Users CRUD
Route::apiResource('users', UserController::class);

// Clubs CRUD
Route::get('clubs/stats', [ClubController::class, 'stats']);
Route::apiResource('clubs', ClubController::class);

// Venues CRUD
Route::get('venues/{id}/schedule', [VenueController::class, 'schedule']);
Route::apiResource('venues', VenueController::class);

// Activities CRUD
Route::post('activities/{id}/register', [ActivityController::class, 'register']);
Route::post('activities/{id}/cancel-registration', [ActivityController::class, 'cancelRegistration']);
Route::apiResource('activities', ActivityController::class);

// Equipment CRUD
Route::post('equipment/borrow', [EquipmentController::class, 'borrow']);
Route::post('equipment/return', [EquipmentController::class, 'returnEquipment']);
Route::post('equipment/remind', [EquipmentController::class, 'remind']);
Route::apiResource('equipment', EquipmentController::class);

// Reservations CRUD
Route::post('reservations/{id}/negotiate', [ReservationController::class, 'negotiate']);
Route::post('reservations/{id}/accept-suggestion', [ReservationController::class, 'acceptSuggestion']);
Route::apiResource('reservations', ReservationController::class)->except(['show']);

// Conflicts (Enhanced Coordination)
Route::get('conflicts', [CrudController::class, 'conflictIndex']);
Route::get('conflicts/{id}', [CrudController::class, 'conflictShow']);
Route::post('conflicts', [CrudController::class, 'conflictStore']);
Route::post('conflicts/negotiate', [CrudController::class, 'conflictNegotiate']);
Route::post('conflicts/{id}/chat', [CrudController::class, 'conflictChat']);
Route::post('conflicts/{id}/send-email', [CrudController::class, 'conflictSendEmail']);
Route::post('conflicts/{id}/confirm', [CrudController::class, 'conflictConfirm']);

// Credits
Route::get('credits/{userId}', [CrudController::class, 'creditShow']);
Route::post('credits/deduct', [CrudController::class, 'creditDeduct']);

// Notifications
Route::get('notifications/{userId}', [CrudController::class, 'notificationIndex']);
Route::post('notifications/{id}/read', [CrudController::class, 'notificationRead']);

// Certificates
Route::post('certificates/generate', [CrudController::class, 'certificateGenerate']);

// Calendar
Route::get('calendar/events', [CrudController::class, 'calendarIndex']);
Route::post('calendar/events', [CrudController::class, 'calendarStore']);
Route::put('calendar/events/{id}', [CrudController::class, 'calendarUpdate']);
Route::delete('calendar/events/{id}', [CrudController::class, 'calendarDestroy']);

// Repairs CRUD
Route::get('repairs', [CrudController::class, 'repairIndex']);
Route::post('repairs', [CrudController::class, 'repairStore']);
Route::put('repairs/{id}', [CrudController::class, 'repairUpdate']);
Route::delete('repairs/{id}', [CrudController::class, 'repairDestroy']);

// Appeals CRUD
Route::get('appeals', [CrudController::class, 'appealIndex']);
Route::post('appeals', [CrudController::class, 'appealStore']);
Route::put('appeals/{id}', [CrudController::class, 'appealUpdate']);
Route::delete('appeals/{id}', [CrudController::class, 'appealDestroy']);
Route::post('appeals/{id}/ai-summary', [CrudController::class, 'appealAiSummary']);

// Dashboard Stats
Route::get('dashboard/stats/{role}', [CrudController::class, 'dashboardStats']);

// AI
Route::post('ai/pre-review', [CrudController::class, 'aiPreReview']);
Route::post('ai/generate-proposal', [CrudController::class, 'aiGenerateProposal']);

// i18n
Route::get('i18n/{lang}', [CrudController::class, 'i18n']);

// Health
Route::get('health', [CrudController::class, 'health']);

// Gatekeeping
Route::post('gatekeeping/check', [CrudController::class, 'gatekeepingCheck']);
