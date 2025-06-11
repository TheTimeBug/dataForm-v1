# Seed Data Summary

## 📊 Database Overview
- **Total Users**: 18
- **Admins**: 10
- **Regular Users**: 8

## 🔐 Admin Users

### 🌍 National Level (1)
| Name | Email | Mobile | Password | Type | Area |
|------|-------|--------|----------|------|------|
| National Admin | national@admin.com | +8801711000001 | password | national | Nationwide |

### 🏛️ Divisional Level (2)
| Name | Email | Mobile | Password | Type | Area |
|------|-------|--------|----------|------|------|
| Dhaka Division Admin | dhaka.division@admin.com | +8801711000002 | password | divisional | Dhaka Division |
| Chittagong Division Admin | chittagong.division@admin.com | +8801711000003 | password | divisional | Chittagong Division |

### 🏢 District Level (3)
| Name | Email | Mobile | Password | Type | Area |
|------|-------|--------|----------|------|------|
| District Admin - [District Name] | district1@admin.com | +8801711000004 | password | district | First District |
| District Admin - [District Name] | district2@admin.com | +8801711000005 | password | district | Second District |
| Secondary District Admin | district.secondary@admin.com | +8801711000009 | password | district | First District (Conflict Test) |

### 🏘️ Upazila Level (3)
| Name | Email | Mobile | Password | Type | Area |
|------|-------|--------|----------|------|------|
| Upazila Admin - [Upazila Name] | upazila1@admin.com | +8801711000006 | password | upazila | First Upazila |
| Upazila Admin - [Upazila Name] | upazila2@admin.com | +8801711000007 | password | upazila | Second Upazila |
| Upazila Admin - [Upazila Name] | upazila3@admin.com | +8801711000008 | password | upazila | Third Upazila |

### ⚠️ Special Status Admin (1)
| Name | Email | Mobile | Password | Type | Status | Reason |
|------|-------|--------|----------|------|--------|--------|
| Suspended Admin | suspended@admin.com | +8801711000010 | password | district | suspended | Under investigation |

## 👥 Regular Users (8)

### ✅ Active Users (7)
| Name | Email | Mobile | Password | Status |
|------|-------|--------|----------|---------|
| John Doe | john@example.com | +8801711111001 | password | active |
| Jane Smith | jane@example.com | +8801711111002 | password | active |
| Ahmed Rahman | ahmed@example.com | +8801711111003 | password | active |
| Fatima Khatun | fatima@example.com | +8801711111004 | password | active |
| Mohammad Hassan | hassan@example.com | +8801711111005 | password | active |
| Rashida Begum | rashida@example.com | +8801711111006 | password | active |
| Test User | user@example.com | +8801711111007 | password | active |

### ⚠️ Special Status User (1)
| Name | Email | Mobile | Password | Status | Reason |
|------|-------|--------|----------|---------|---------|
| Inactive User | inactive@example.com | +8801711111008 | password | inactive | Account temporarily suspended for verification |

## 🧪 Testing Features

### 🔀 Conflict Resolution Testing
- **Primary District Admin**: district1@admin.com (First District)
- **Secondary District Admin**: district.secondary@admin.com (Same First District)
- This setup allows testing the conflict resolution modal when creating new admins for areas that already have admins.

### 📊 Admin Type Testing
- **National**: 1 admin covering all areas
- **Divisional**: 2 admins covering different divisions
- **District**: 3 admins covering different districts (2 unique + 1 conflict)
- **Upazila**: 3 admins covering different upazilas

### 🚦 Status Testing
- **Active**: Most users and admins
- **Inactive**: 1 regular user
- **Suspended**: 1 admin

## 🚀 Login Instructions

### For Admin Panel Access:
1. Visit: `http://127.0.0.1:8000`
2. Use any admin email from the list above
3. Password: `password`
4. Navigate to **Users → Admins** to manage admin users

### Testing Recommendations:
1. **Login as National Admin** to see all functionality
2. **Test conflict resolution** by creating a new admin for an area that already has one
3. **Test different admin types** and their area selection requirements
4. **Test search and filtering** with the variety of seeded data

## 📋 Features to Test

✅ **Admin Creation with Area Selection**
✅ **Conflict Detection and Resolution**
✅ **Search and Filter Functionality** 
✅ **Different Admin Types**
✅ **Area Hierarchy Display**
✅ **Status Management**
✅ **Mobile Number Integration**
✅ **Password Generation** 