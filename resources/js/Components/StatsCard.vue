<template>
  <StatCard v-bind="$attrs" v-on="$listeners">
    <template v-for="(_, slot) in $slots" v-slot:[slot]="scope">
      <slot :name="slot" v-bind="scope || {}"/>
    </template>
  </StatCard>
</template>

<script setup>
import StatCard from './StatCard.vue'

// Alias vers StatCard pour uniformité avec la référence UI Dashboard
// Permet d'utiliser <StatsCard> ou <StatCard> indifféremment
</script>
