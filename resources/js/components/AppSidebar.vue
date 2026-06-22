<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Fish, Tag, ShoppingCart, ArrowLeftRight } from '@lucide/vue';
import AppLogo from '@/components/AppLogo.vue';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

// 1. Ambil data user yang sedang login
const page = usePage();
const userRole = page.props.auth.user.role;

// 2. Gunakan "computed" agar daftar menu menyesuaikan dengan role user
const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Items',
            href: '/items',
            icon: Fish,
        },
        {
            title: 'Kode Items',
            href: '/kode-items',
            icon: Tag,
        },
        {
            title: 'Transaksi',
            href: '/transaksi',
            icon: ShoppingCart,
        },
        {
            title: 'Mutasi Stok',
            href: '/mutasi-stok',
            icon: ArrowLeftRight,
        },
    ];

    // Jika admin, tampilkan semua menu
    if (userRole === 'admin') {
        return items;
    } 
    
    // Jika pegawai, filter dan HANYA tampilkan Dashboard & Transaksi
    return items.filter(item => item.title === 'Dashboard' || item.title === 'Transaksi');
});

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">

        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <!-- NavMain sekarang akan otomatis membaca hasil filter dari computed -->
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>

    </Sidebar>
    <slot />
</template>