<script setup lang="ts">
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/Components/ui/breadcrumb'

defineProps<{
  breadcrumbs: Array<{
    label: string
    route?: string
  }>
}>()
</script>

<template>
  <Breadcrumb>
    <BreadcrumbList>
      <template v-for="(breadcrumb, index) in breadcrumbs">
        <BreadcrumbItem>
          <BreadcrumbLink v-if="breadcrumb.route" :href="breadcrumb.route">
            {{ breadcrumb.label }}
          </BreadcrumbLink>
          <BreadcrumbPage v-else>{{ breadcrumb.label }}</BreadcrumbPage>
        </BreadcrumbItem>
        <BreadcrumbSeparator v-if="index !== breadcrumbs.length - 1" />
      </template>
    </BreadcrumbList>
  </Breadcrumb>
</template>
