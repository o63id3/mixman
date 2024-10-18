<script setup lang="ts">
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
  FormDescription,
} from '@/Components/ui/form'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/Components/ui/select'
import { Input } from '@/Components/ui/input'
import { Textarea } from '@/Components/ui/textarea'

import { roles } from '@/types/enums'
import { Network } from '@/types'
import { onMounted, ref } from 'vue'

defineProps<{
  disabled?: boolean
  networks: Array<Network>
  role?: string
}>()

const input = ref<InstanceType<typeof Input> | null>(null)
onMounted(() => {
  input.value?.inputElement?.focus()
})
</script>

<template>
  <FormField v-slot="{ componentField }" name="name">
    <FormItem>
      <FormLabel>الاسم</FormLabel>
      <FormControl>
        <Input
          ref="input"
          type="text"
          v-bind="componentField"
          :disabled="disabled"
        />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="role">
    <FormItem>
      <FormLabel>الصلاحية</FormLabel>
      <FormControl>
        <Select v-bind="componentField">
          <SelectTrigger>
            <SelectValue placeholder="اختر صلاحية" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem
                v-for="role in roles.filter((role) =>
                  $page.props.auth.user.role === 'ahmed'
                    ? true
                    : role.value === 'seller',
                )"
                :key="role.value"
                :value="role.value"
              >
                <div class="flex items-center justify-end gap-1">
                  {{ role.label }}
                  <component :is="role.icon" class="w-4" />
                </div>
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="username">
    <FormItem>
      <FormLabel>اسم المستخدم</FormLabel>
      <FormControl>
        <Input
          type="text"
          v-bind="componentField"
          autocapitalize="none"
          :disabled="disabled"
        />
      </FormControl>
      <FormDescription>
        سيتم استخدام هذا الاسم في عملية تسجيل الدخول.
      </FormDescription>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="password">
    <FormItem>
      <FormLabel>كلمة المرور</FormLabel>
      <FormControl>
        <Input
          type="password"
          placeholder="********"
          v-bind="componentField"
          :disabled="disabled"
        />
      </FormControl>
      <FormDescription> سيتم استخدامها في عملية تسجيل الدخول. </FormDescription>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField
    v-if="['ahmed', 'partner'].includes(role ?? '')"
    v-slot="{ componentField }"
    name="telegram"
  >
    <FormItem>
      <FormLabel>حساب التلغرام</FormLabel>
      <FormControl>
        <Input
          type="text"
          v-bind="componentField"
          autocapitalize="none"
          :disabled="disabled"
        />
      </FormControl>
      <FormDescription>
        سيتم استخدام هذا الحساب في إرسال الاشعارات.
      </FormDescription>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField
    v-if="role === 'seller'"
    v-slot="{ componentField }"
    name="network_id"
  >
    <FormItem>
      <FormLabel>الشبكة</FormLabel>
      <FormControl>
        <Select v-bind="componentField">
          <SelectTrigger>
            <SelectValue placeholder="اختر  الشبكة" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup>
              <SelectItem
                v-for="network in networks"
                :key="network.id"
                :value="String(network.id)"
              >
                {{ network.name }}
              </SelectItem>
            </SelectGroup>
          </SelectContent>
        </Select>
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField
    v-if="['seller', 'partner'].includes(role ?? '')"
    v-slot="{ componentField }"
    name="percentage"
  >
    <FormItem>
      <FormLabel>حصة البائع (%)</FormLabel>
      <FormControl>
        <Input type="number" v-bind="componentField" :disabled="disabled" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
  <FormField v-slot="{ componentField }" name="contact_info">
    <FormItem class="md:col-span-2">
      <FormLabel>بيانات التواصل</FormLabel>
      <FormControl>
        <Textarea v-bind="componentField" :disabled="disabled" />
      </FormControl>
      <FormMessage />
    </FormItem>
  </FormField>
</template>
