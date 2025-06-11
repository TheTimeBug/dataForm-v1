<script>
/**
 * Optimized Laravel-level Authorization System for Frontend
 * Single API call with caching for better performance
 */

class AuthorizationManager {
    constructor() {
        this.baseUrl = '/api';
        this.initialized = false;
        this.authData = null;
    }

    async init() {
        if (this.initialized) return this.authData;
        
        try {
            // Single API call to get all authorization info
            const response = await axios.get(`${this.baseUrl}/admin/me`);
            const userData = response.data;
            
            // Cache all authorization data
            this.authData = {
                user: userData,
                isAdmin: userData.role === 'admin',
                isSuperAdmin: userData.admin_type === 'superadmin',
                adminType: userData.admin_type,
                adminLevel: this.getAdminLevel(userData.admin_type),
                divisionId: userData.division_id,
                districtId: userData.district_id,
                upazilaId: userData.upazila_id
            };
            
            this.initialized = true;
            console.log('Authorization initialized:', this.authData);
            return this.authData;
            
        } catch (error) {
            console.error('Authorization init failed:', error);
            this.handleAuthError(error);
            return null;
        }
    }

    // Fast cached authorization checks
    isAdminAuthorized() {
        return this.authData?.isAdmin || false;
    }

    isSuperAdminAuthorized() {
        return this.authData?.isSuperAdmin || false;
    }

    hasAdminLevel(requiredLevel) {
        if (!this.authData) return false;
        const requiredLevelNum = this.getAdminLevel(requiredLevel);
        return this.authData.adminLevel >= requiredLevelNum;
    }

    canAccessArea(divisionId = null, districtId = null, upazilaId = null) {
        if (!this.authData) return false;
        
        // Superadmin and national can access all areas
        if (this.authData.adminType === 'superadmin' || this.authData.adminType === 'national') {
            return true;
        }

        // Divisional admin can only access their division
        if (this.authData.adminType === 'divisional') {
            return !divisionId || this.authData.divisionId == divisionId;
        }

        // District admin can only access their district
        if (this.authData.adminType === 'district') {
            return (!divisionId || this.authData.divisionId == divisionId) && 
                   (!districtId || this.authData.districtId == districtId);
        }

        // Upazila admin can only access their upazila
        if (this.authData.adminType === 'upazila') {
            return (!divisionId || this.authData.divisionId == divisionId) && 
                   (!districtId || this.authData.districtId == districtId) && 
                   (!upazilaId || this.authData.upazilaId == upazilaId);
        }

        return false;
    }

    // Legacy API methods for backward compatibility (now use cached data)
    async checkAdminAccess() {
        if (!this.initialized) await this.init();
        return this.isAdminAuthorized();
    }

    async checkSuperAdminAccess() {
        if (!this.initialized) await this.init();
        return this.isSuperAdminAuthorized();
    }

    async checkAdminLevel(requiredLevel) {
        if (!this.initialized) await this.init();
        return this.hasAdminLevel(requiredLevel);
    }

    async checkAreaAccess(divisionId = null, districtId = null, upazilaId = null) {
        if (!this.initialized) await this.init();
        return this.canAccessArea(divisionId, districtId, upazilaId);
    }

    // Enforcement methods
    async enforceAdminAccess() {
        const authorized = await this.checkAdminAccess();
        if (!authorized) {
            this.redirectToUnauthorized();
        }
        return authorized;
    }

    async enforceSuperAdminAccess() {
        const authorized = await this.checkSuperAdminAccess();
        if (!authorized) {
            this.redirectToUnauthorized();
        }
        return authorized;
    }

    async enforceAdminLevel(requiredLevel) {
        const authorized = await this.checkAdminLevel(requiredLevel);
        if (!authorized) {
            this.redirectToUnauthorized();
        }
        return authorized;
    }

    // Optimized UI management - no API calls needed
    async showUIElementsBasedOnAuth() {
        if (!this.initialized) await this.init();
        if (!this.authData) return;

        // Show/hide superadmin-only elements
        const superAdminElements = document.querySelectorAll('[data-superadmin-only="true"]');
        superAdminElements.forEach(element => {
            element.style.display = this.isSuperAdminAuthorized() ? 'block' : 'none';
        });

        // Show/hide admin-level elements
        const adminLevelElements = document.querySelectorAll('[data-admin-level]');
        adminLevelElements.forEach(element => {
            const requiredLevel = element.getAttribute('data-admin-level');
            element.style.display = this.hasAdminLevel(requiredLevel) ? 'block' : 'none';
        });

        console.log('UI elements updated based on authorization');
    }

    // Utility methods
    toggleElementsByAdminType(elementId, requiredAdminType) {
        const element = document.getElementById(elementId);
        if (!element) return;
        element.style.display = this.hasAdminLevel(requiredAdminType) ? 'block' : 'none';
    }

    toggleElementsBySuperAdmin(elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;
        element.style.display = this.isSuperAdminAuthorized() ? 'block' : 'none';
    }

    getAdminLevel(adminType) {
        const levels = {
            'upazila': 1,
            'district': 2,
            'divisional': 3,
            'national': 4,
            'superadmin': 5
        };
        return levels[adminType] || 0;
    }

    handleAuthError(error) {
        if (error.response?.status === 401) {
            // Token expired or invalid
            this.clearAuthData();
            window.location.href = '{{ route("admin.login") }}';
        } else if (error.response?.status === 403) {
            // Insufficient privileges
            this.redirectToUnauthorized();
        }
    }

    redirectToUnauthorized() {
        window.location.href = '{{ route("unauthorized") }}';
    }

    clearAuthData() {
        localStorage.removeItem('admin_token');
        localStorage.removeItem('user_token');
        localStorage.removeItem('user_data');
        this.authData = null;
        this.initialized = false;
    }

    // Get current user info
    getCurrentUser() {
        return this.authData?.user || null;
    }

    // Get current admin type
    getCurrentAdminType() {
        return this.authData?.adminType || null;
    }

    // Get current admin level
    getCurrentAdminLevel() {
        return this.authData?.adminLevel || 0;
    }
}

// Global authorization manager instance
window.authManager = new AuthorizationManager();

// Initialize on page load with performance monitoring
document.addEventListener('DOMContentLoaded', async function() {
    if (localStorage.getItem('admin_token')) {
        try {
            console.time('Authorization Init');
            await window.authManager.init();
            await window.authManager.showUIElementsBasedOnAuth();
            console.timeEnd('Authorization Init');
        } catch (error) {
            console.error('Failed to initialize authorization:', error);
        }
    }
});
</script> 