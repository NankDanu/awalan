# GitHub Copilot Prompt — Aplikasi catat

**Status Project:** Laravel 12 Boilerplate + Modul Baru "catat"  
**Tujuan:** Dokumentasi hub untuk agensi/freelancer IT  
**Fase Implementasi:** 5 fase (eksekusi bertahap)

---

## KONTEKS PROYEK

### Stack Teknologi
| Area | Teknologi |
|------|-----------|
| **Backend** | PHP 8.2+, Laravel 12 |
| **Frontend** | Blade, Tailwind CSS 3, Vite 5, Axios |
| **UI Utility** | KTUI |
| **Auth** | Laravel Sanctum |
| **Authorization** | Spatie Laravel Permission |
| **Database** | MySQL 8.0+ |
| **Cache & Queue** | Database driver |

### Struktur Folder yang Ada
```
app/
├── Helpers/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Observers/
├── Providers/
└── Services/

database/
├── factories/
├── migrations/
└── seeders/

resources/
├── css/
├── js/
└── views/

routes/
├── api.php
├── console.php
└── web.php
```

---

## KONVENSI CODING YANG HARUS DIIKUTI

### 1. **Arsitektur Aplikasi**
- ✅ **Controller tipis** — hanya handle request/response, logika di Services
- ✅ **Form Request** — untuk semua validasi input (namespace: `app/Http/Requests/`)
- ✅ **Service Layer** — operasi kompleks di `app/Services/`
- ✅ **Observer** — untuk side effects (cache invalidation, auto-create, activity log)

### 2. **Database & Model**
- ✅ **UUID** sebagai primary key di semua tabel baru (`unsignedBigInteger + uuid`)
- ✅ **Prefix tabel:** `ct_` untuk semua tabel modul catat
  - Contoh: `ct_clients`, `ct_projects`, `ct_nodes`, `ct_activity_logs`
- ✅ **Soft Delete** — `softDeletes()` di semua tabel utama
- ✅ **Timestamps:** `created_at`, `updated_at`, `deleted_at`

### 3. **Naming Conventions**
- Model: PascalCase (misal: `CatatNode`, `CatatProject`)
- Table: snake_case dengan prefix `ct_` (misal: `ct_catat_nodes`)
- Controller: `NamaController` (misal: `ProjectController`)
- Service: `NamaService` (misal: `ProjectService`)
- Observer: `NamaObserver` (misal: `ProjectObserver`)
- Request: `StoreNamaRequest`, `UpdateNamaRequest`

---

## KONSEP DATA APLIKASI

### Hierarki Data
```
Client (ct_clients)
└── Project (ct_projects) [Workspace]
    ├── Status: lead → ongoing → maintenance → closed
    ├── Type tag: one-time | maintenance | saas | retainer
    ├── Nodes (ct_nodes) [Tree struktur seperti file system]
    │   ├── Folder (type: folder, bisa nested unlimited)
    │   │   ├── Sub-folder
    │   │   └── Note (.md)
    │   └── Note (type: note, markdown format)
    └── Project Links (ct_project_links)
        ├── Git repo (staging/production/legacy/backup)
        ├── External storage (Google Drive/NAS/lokal)
        ├── Password manager
        └── Custom link
```

### Aturan Bisnis Utama
1. **Closed Projects** → Otomatis masuk modul Archive, keluar dari workspace aktif
2. **New Project** → Auto-create 4 folder default:
   - Requests
   - Meeting Notes
   - SOP
   - Free Notes
3. **Dokumen (Node)** → Konten dalam format Markdown (disimpan di `ct_nodes.content`)
4. **Links** → Hanya menyimpan URL, tidak upload file
5. **Activity Log** → Semua perubahan dokumen dicatat di `ct_activity_logs`

---

## FASE IMPLEMENTASI

### 📋 FASE 1: Database Setup
**Tujuan:** Buat semua tabel dan model dengan relasi

**Deliverable:**
- [ ] Migration: `ct_clients` table
- [ ] Migration: `ct_projects` table (dengan status, type enum)
- [ ] Migration: `ct_nodes` table (parent_id untuk nested structure)
- [ ] Migration: `ct_project_links` table
- [ ] Migration: `ct_activity_logs` table
- [ ] Model: `CatatClient` dengan relationships
- [ ] Model: `CatatProject` dengan relationships & scopes
- [ ] Model: `CatatNode` dengan recursive relationships
- [ ] Model: `CatatProjectLink` model
- [ ] Model: `CatatActivityLog` model

**Eksekusi:**
```
Buatkan migration dan model untuk fase 1 sesuai konsep aplikasi di atas.
Pastikan:
- Semua tabel pakai prefix ct_
- UUID sebagai pk (kecuali activity logs auto-increment)
- Soft delete di tabel utama
- Relasi antar model sudah terdefinisi
- Timestamps di semua tabel
```

---

### 🔒 FASE 2: Authentication & Authorization
**Tujuan:** Setup Sanctum + Permission roles untuk catat module

**Deliverable:**
- [ ] Sanctum token setup
- [ ] Permission & roles untuk catat:
  - `catat.view` — view projects/docs
  - `catat.create` — create projects/nodes
  - `catat.edit` — edit projects/nodes
  - `catat.delete` — delete projects/nodes
  - `catat.manage` — manage permissions & links
- [ ] Seeder: `CatatPermissionSeeder`
- [ ] User → Role association (pivot table jika belum ada)

**Eksekusi:**
```
Buatkan Sanctum setup dan permission/role untuk modul catat.
Pastikan:
- Spatie permission module sudah tersetup
- Minimal 4 permissions di atas sudah di-seed
- Hubungan user-role-permission sudah jelas
```

---

### 🏗️ FASE 3: Service & Observer Layer
**Tujuan:** Logika bisnis dan side effects

**Deliverable:**
- [ ] Service: `CatatProjectService` — CRUD + auto-create default folders
- [ ] Service: `CatatNodeService` — CRUD nodes + Markdown storage
- [ ] Service: `CatatActivityLogService` — logging & history
- [ ] Observer: `CatatProjectObserver` — trigger default folder creation
- [ ] Observer: `CatatNodeObserver` — log activity, invalidate cache
- [ ] Observer: `CatatProjectLinkObserver` — validate URLs

**Business Logic Details:**
- Saat project dibuat → auto-create 4 folder (Requests, Meeting Notes, SOP, Free Notes)
- Saat node di-update → log activity dengan old/new content
- Saat project status jadi "closed" → move ke archive (hidden dari workspace utama)

**Eksekusi:**
```
Buatkan Service dan Observer untuk catat module dengan logic berikut:
1. ProjectService: auto-create 4 default folders
2. NodeService: simpan/update markdown content
3. ActivityLogService: catat setiap perubahan
4. Observers: trigger events di atas
```

---

### 🚀 FASE 4: API Routes & Controllers
**Tujuan:** REST API endpoints untuk catat

**Deliverable:**
- [ ] Controller: `CatatProjectController` (index, show, store, update, destroy)
- [ ] Controller: `CatatNodeController` (index, show, store, update, destroy)
- [ ] Controller: `CatatProjectLinkController` (index, show, store, destroy)
- [ ] Controller: `CatatActivityLogController` (index, show)
- [ ] Form Request: `StoreCatatProjectRequest` + `UpdateCatatProjectRequest`
- [ ] Form Request: `StoreCatatNodeRequest` + `UpdateCatatNodeRequest`
- [ ] Form Request: `StoreCatatProjectLinkRequest`
- [ ] Routes: `/api/catat/*` dengan auth middleware

**API Endpoints:**
```
GET    /api/catat/projects              — List projects
POST   /api/catat/projects              — Create project
GET    /api/catat/projects/{id}         — Show project
PUT    /api/catat/projects/{id}         — Update project
DELETE /api/catat/projects/{id}         — Delete project

GET    /api/catat/projects/{id}/nodes   — List nodes in project
POST   /api/catat/projects/{id}/nodes   — Create node
GET    /api/catat/nodes/{id}            — Show node
PUT    /api/catat/nodes/{id}            — Update node
DELETE /api/catat/nodes/{id}            — Delete node

GET    /api/catat/projects/{id}/links   — List project links
POST   /api/catat/projects/{id}/links   — Create link
DELETE /api/catat/links/{id}            — Delete link

GET    /api/catat/nodes/{id}/activity   — Activity history
```

**Eksekusi:**
```
Buatkan controllers dan routes untuk catat API endpoints.
Pastikan:
- Thin controller (logika di service)
- Form Request validation
- Proper HTTP status codes (201, 204, 422, 403)
- Authorization checks (@can directive atau in controller)
- Pagination untuk list endpoints
```

---

### 🎨 FASE 5: Frontend Views
**Tujuan:** Blade views + Axios integration dengan Tailwind CSS

**Deliverable:**
- [ ] Layout: `resources/views/catat/layout.blade.php`
- [ ] View: `resources/views/catat/projects/index.blade.php`
- [ ] View: `resources/views/catat/projects/show.blade.php`
- [ ] View: `resources/views/catat/projects/create-edit.blade.php`
- [ ] View: `resources/views/catat/nodes/editor.blade.php` (Markdown editor)
- [ ] Component: `resources/views/components/catat/node-tree.blade.php`
- [ ] Component: `resources/views/components/catat/markdown-editor.blade.php`
- [ ] JS: `resources/js/catat/api-client.js` (Axios wrapper)
- [ ] JS: `resources/js/catat/tree-manager.js` (Handle tree interactions)

**UI/UX Considerations:**
- Gunakan Tailwind CSS 3 untuk styling
- Responsive design (mobile-first)
- Tree view untuk nested nodes (folder/file structure)
- Markdown editor dengan preview
- Activity log sidebar
- Status badge untuk project (lead, ongoing, maintenance, closed)
- Tag chips untuk project type

**Eksekusi:**
```
Buatkan views dan frontend components untuk catat module.
Pastikan:
- Menggunakan Blade + Tailwind CSS 3
- Axios untuk API calls
- Tree view untuk nested folders
- Markdown editor + preview
- Responsive mobile
- Styling konsisten dengan project existing
```

---

## QUICK COMMAND REFERENCE

### Eksekusi Fase Demi Fase (Gunakan di Copilot Chat)
```
# Copy-paste prompt untuk fase spesifik di bawah ini ke Copilot Chat

### FASE 1: Database & Models Setup
Kamu adalah senior Laravel developer. Buatkan database migrations dan Models untuk modul catat dengan detail berikut:

**Context:**
- Project: Dokumentasi hub untuk IT agencies/freelancer
- Stack: Laravel 12, PHP 8.2+, MySQL 8.0+
- Struktur: Client > Project > Nodes (tree) > Links

**Requirements:**
1. Tabel `ct_clients` (uuid pk, nama, deskripsi, soft delete)
2. Tabel `ct_projects` (uuid pk, client_id FK, nama, status enum[lead|ongoing|maintenance|closed], type tag[one-time|maintenance|saas|retainer], deskripsi, soft delete)
3. Tabel `ct_nodes` (uuid pk, project_id FK, parent_id nullable FK self-reference, type enum[folder|note], nama, konten md text, soft delete)
4. Tabel `ct_project_links` (uuid pk, project_id FK, tipe[git|storage|password|custom], label, url, soft delete)
5. Tabel `ct_activity_logs` (id auto, user_id FK, node_id FK, action, old_value, new_value, created_at)

**Model Relations:**
- CatatClient: hasMany Projects
- CatatProject: belongsTo Client, hasMany Nodes, hasMany ProjectLinks
- CatatNode: belongsTo Project, belongsTo Parent (self), hasMany Children, hasManyThrough ActivityLogs
- CatatProjectLink: belongsTo Project
- CatatActivityLog: belongsTo User, belongsTo Node

**Semua tabel pakai:**
- UUID sebagai pk (kecuali activity logs pakai id auto)
- timestamps (created_at, updated_at)
- soft delete (deleted_at) untuk tabel utama
- Foreign key dengan cascade delete

Buatkan migration dan model dengan relasi sudah terdefinisi.
```

### Test Routes Setup (Jika sudah selesai semua fase)
```bash
# Test API
php artisan tinker
# atau
curl -X GET http://localhost:8000/api/catat/projects

# Run migration
php artisan migrate

# Seed permissions
php artisan db:seed --class=CatatPermissionSeeder

# Run tests (saat ada test suite)
php artisan test
```

---

## INFORMASI TAMBAHAN

### Tools & Utilities
- **Markdown Parser:** gunakan `league/commonmark` (sudah di composer.json)
- **UUID Generator:** Laravel 12 built-in (uuid helper)
- **Cache:** gunakan `cache()` helper, driver database
- **Queue:** untuk activity logging berat, gunakan queue

### Documentation Structure
- Semua dokumentasi di `/docs/`
- Maintain API docs saat menambah endpoint baru
- Update reference docs per fase

### Testing Strategy
- Unit test: Model relationships, Service logic
- Feature test: API endpoints, Permission checks
- Folder: `tests/Feature/Catat/`, `tests/Unit/Catat/`

---

## ✅ CHECKLIST FINAL

Sebelum mark fase sebagai "Done", pastikan:
- [ ] Code sudah ikuti naming conventions & folder structure
- [ ] Migration runnable dengan `php artisan migrate`
- [ ] Model relationships sudah tested
- [ ] Service logic test coverage > 80%
- [ ] API endpoints return correct HTTP status
- [ ] Authorization checks sudah di-enforce
- [ ] Frontend responsive dan fungsional
- [ ] Tidak ada hardcoded values (gunakan config/env)
- [ ] Database seeders siap untuk development
- [ ] Activity logging berjalan untuk setiap perubahan

---

**Last Updated:** April 2026  
**Version:** 1.0  
**Project:** catat (Documentation Hub)
