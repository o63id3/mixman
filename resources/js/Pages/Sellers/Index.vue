<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Paginator, User } from '@/types'
import { Head, Link } from '@inertiajs/vue3'

import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/Components/ui/table'

import {
  Pagination,
  PaginationList,
  PaginationListItem,
} from '@/Components/ui/pagination'

import { Button } from '@/Components/ui/button'

defineProps<{
  sellers: Paginator<User>
}>()
</script>

<template>
  <Head title="Sellers" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          نقاط البيع
        </h2>
        <div v-if="$page.props.auth.user.can.sellers.create">
          <Link :href="route('sellers.create')">
            <Button> إنشاء نقطة بيع </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <Table>
              <TableCaption>حسابات نقاط البيع</TableCaption>
              <TableHeader>
                <TableRow>
                  <TableHead class="w-[50px] text-right">#</TableHead>
                  <TableHead class="w-1/4 text-right">الاسم</TableHead>
                  <TableHead class="w-1/4 text-right">اسم المستخدم</TableHead>
                  <TableHead class="w-1/4 text-right">المنطقة</TableHead>
                  <TableHead class="w-1/4 text-right">الحساب</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="seller in sellers.data" :key="seller.id">
                  <TableCell class="font-medium hover:underline">
                    <component
                      :is="
                        $page.props.auth.user.can.sellers.update ? Link : 'span'
                      "
                      :href="route('sellers.edit', seller.id)"
                    >
                      {{ seller.id }}
                    </component>
                  </TableCell>
                  <TableCell>{{ seller.name }}</TableCell>
                  <TableCell>{{ seller.username }}</TableCell>
                  <TableCell class="text-right">
                    {{ seller.region.name }}
                  </TableCell>
                  <TableCell class="text-right">{{ seller.balance }}</TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <Pagination
              v-slot="{ page }"
              :total="sellers.meta.total"
              :sibling-count="1"
              show-edges
              :default-page="sellers.meta.current_page"
            >
              <PaginationList
                v-slot="{ items }"
                class="flex items-center gap-1"
              >
                <template v-for="(item, index) in items">
                  <PaginationListItem
                    v-if="item.type === 'page'"
                    :key="index"
                    :value="item.value"
                    as-child
                  >
                    <Link
                      :href="route('sellers.index', { page: item.value })"
                      preserve-scroll
                    >
                      <Button
                        class="h-10 w-10 p-0"
                        :variant="item.value === page ? 'default' : 'outline'"
                      >
                        {{ item.value }}
                      </Button>
                    </Link>
                  </PaginationListItem>
                </template>
              </PaginationList>
            </Pagination>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
