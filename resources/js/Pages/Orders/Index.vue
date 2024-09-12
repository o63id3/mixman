<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Order, Paginator } from '@/types'
import { Head, Link } from '@inertiajs/vue3'

import {
  Table,
  TableBody,
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
import { CheckCircle2Icon, CircleDashed, XCircleIcon } from 'lucide-vue-next'

defineProps<{
  orders: Paginator<Order>
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
          الطلبات
        </h2>
        <div v-if="$page.props.auth.user.can.orders.create">
          <Link :href="route('orders.create')">
            <Button> طلبية جديدة </Button>
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <Table>
              <TableHeader>
                <TableRow>
                  <TableHead class="w-[50px] text-right">#</TableHead>
                  <TableHead class="w-1/2 text-right">البائع</TableHead>
                  <TableHead class="w-5 text-right">الحالة</TableHead>
                  <TableHead class="w-1/2 text-right">
                    تم اتخاذ الاجراء بواسطة
                  </TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow v-for="order in orders.data" :key="order.id">
                  <TableCell>
                    {{ order.id }}
                  </TableCell>
                  <TableCell class="font-medium hover:underline">
                    <component
                      :is="order.can.view ? Link : 'span'"
                      :href="route('orders.edit', order.id)"
                    >
                      {{ order.seller.name }}
                    </component>
                  </TableCell>
                  <TableCell>
                    <CheckCircle2Icon
                      v-if="order.status === 'C'"
                      class="text-green-500"
                    />
                    <XCircleIcon
                      v-if="order.status === 'X'"
                      class="text-red-500"
                    />
                    <CircleDashed
                      v-if="order.status === 'P'"
                      class="text-yellow-500"
                    />
                  </TableCell>
                  <TableCell>{{ order.action?.name }}</TableCell>
                </TableRow>
              </TableBody>
            </Table>

            <Pagination
              v-slot="{ page }"
              :total="orders.meta.total"
              :sibling-count="1"
              show-edges
              :default-page="orders.meta.current_page"
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
                      :href="route('orders.index', { page: item.value })"
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
