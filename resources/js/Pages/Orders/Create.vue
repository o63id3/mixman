<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { Button } from '@/Components/ui/button'
import {
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from '@/Components/ui/form'
import { toast } from '@/Components/ui/toast'
import {
  Command,
  CommandEmpty,
  CommandGroup,
  CommandInput,
  CommandItem,
  CommandList,
} from '@/Components/ui/command'
import Textarea from '@/Components/ui/textarea/Textarea.vue'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectTrigger,
  SelectValue,
  SelectLabel,
} from '@/Components/ui/select'
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from '@/Components/ui/popover'
import { Card, User } from '@/types'
import { cn } from '@/lib/utils'
import { CheckIcon, PlusCircleIcon, Trash2Icon } from 'lucide-vue-next'
import { CaretSortIcon } from '@radix-icons/vue'
import { Input } from '@/Components/ui/input'
import Label from '@/Components/ui/label/Label.vue'

const formSchema = toTypedSchema(
  z.object({
    seller_id: z.number({ message: 'هذا الحقل مطلوب' }),
    status: z.string({ message: 'هذا الحقل مطلوب' }),
    cards: z.array(
      z.object({
        card_id: z.string({ message: 'حقل الفئة مطلوب' }),
        number_of_packages: z.number({ message: 'حقل الفئة مطلوب' }).min(1),
        number_of_cards_per_package: z.number(),
      }),
    ),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
  initialValues: {
    status: 'P',
    cards: [
      {
        card_id: '1',
        number_of_packages: 1,
        number_of_cards_per_package: 120,
      },
    ],
  },
})

const onSubmit = handleSubmit((values) => {
  router.post(route('orders.store'), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إنشاء الطلب' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})

defineProps<{
  sellers: Array<User>
  cards: Array<Card>
  statuses: Array<string>
}>()
</script>

<template>
  <Head title="Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        طلبية جديدة
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form class="space-y-6" @submit="onSubmit">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <FormField name="seller_id">
                  <FormItem class="flex flex-col gap-2">
                    <FormLabel>نقطة البيع</FormLabel>
                    <Popover>
                      <PopoverTrigger as-child>
                        <FormControl>
                          <Button
                            variant="outline"
                            role="combobox"
                            :class="
                              cn(
                                'justify-between',
                                !values.seller_id && 'text-muted-foreground',
                              )
                            "
                          >
                            {{
                              values.seller_id
                                ? sellers.find(
                                    (seller) => seller.id === values.seller_id,
                                  )?.name
                                : 'اختر نقطة بيع...'
                            }}
                            <CaretSortIcon
                              class="ml-2 h-4 w-4 shrink-0 opacity-50"
                            />
                          </Button>
                        </FormControl>
                      </PopoverTrigger>
                      <PopoverContent class="w-full p-0">
                        <Command>
                          <CommandInput
                            class="text-right"
                            placeholder="ابحث عن نقطة بيع"
                          />
                          <CommandEmpty>لا يوجد تطابق!</CommandEmpty>
                          <CommandList>
                            <CommandGroup>
                              <CommandItem
                                v-for="seller in sellers"
                                :key="seller.id"
                                :value="seller.name"
                                @select="
                                  () => setFieldValue('seller_id', seller.id)
                                "
                              >
                                <div
                                  class="flex w-full items-center justify-between"
                                >
                                  <CheckIcon
                                    :class="
                                      cn(
                                        'ml-auto h-4 w-4',
                                        seller.id === values.seller_id
                                          ? 'opacity-100'
                                          : 'opacity-0',
                                      )
                                    "
                                  />
                                  <div class="flex-1 text-right">
                                    {{ seller.name }}
                                  </div>
                                </div>
                              </CommandItem>
                            </CommandGroup>
                          </CommandList>
                        </Command>
                      </PopoverContent>
                    </Popover>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="status">
                  <FormItem>
                    <FormLabel>الحالة</FormLabel>
                    <FormControl>
                      <Select v-bind="componentField">
                        <SelectTrigger>
                          <SelectValue placeholder="اختر حالة الطلب" />
                        </SelectTrigger>
                        <SelectContent>
                          <SelectGroup>
                            <SelectItem
                              v-for="status in statuses"
                              :key="status"
                              :value="status"
                            >
                              {{ status }}
                            </SelectItem>
                          </SelectGroup>
                        </SelectContent>
                      </Select>
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="cards">
                  <FormItem class="md:col-span-2">
                    <FormLabel>الكروت</FormLabel>
                    <div class="flex w-full">
                      <Label class="w-1/3 text-xs"> الفئة </Label>
                      <Label class="w-1/3 text-xs"> عدد الرزم </Label>
                      <Label class="w-1/3 text-xs">
                        عدد الكروت في كل رزمة
                      </Label>
                      <div class="w-12"></div>
                    </div>
                    <FormControl class="col-span-2">
                      <div
                        v-for="(card, index) in componentField.modelValue"
                        :key="index"
                        class="mb-2 flex gap-1"
                      >
                        <Select v-model="card.card_id">
                          <SelectTrigger>
                            <SelectValue placeholder="اختر الفئة" />
                          </SelectTrigger>
                          <SelectContent>
                            <SelectGroup>
                              <SelectLabel>الفئات</SelectLabel>
                              <SelectItem
                                v-for="cardOption in cards"
                                :key="cardOption.id"
                                :value="String(cardOption.id)"
                              >
                                {{ cardOption.name }}
                              </SelectItem>
                            </SelectGroup>
                          </SelectContent>
                        </Select>

                        <Input
                          v-model="card.number_of_packages"
                          type="number"
                          placeholder="عدد الرزم"
                        />
                        <Input
                          v-model="card.number_of_cards_per_package"
                          type="number"
                          placeholder="عدد الكروت في الرزمة"
                        />

                        <Button
                          type="button"
                          variant="destructive"
                          @click="componentField.modelValue.splice(index, 1)"
                          :disabled="componentField.modelValue.length < 2"
                        >
                          <Trash2Icon class="h-5 w-5" />
                        </Button>
                      </div>

                      <Button
                        type="button"
                        variant="outline"
                        @click="
                          componentField.modelValue.push({
                            card_id: '1',
                            number_of_packages: 1,
                            number_of_cards_per_package: 120,
                          })
                        "
                      >
                        <PlusCircleIcon class="w-5" />
                      </Button>
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="notes">
                  <FormItem class="md:col-span-2">
                    <FormLabel>ملاحظات</FormLabel>
                    <FormControl>
                      <Textarea v-bind="componentField" />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                </FormField>
              </div>
              <Button type="submit"> إنشاء </Button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
