import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { TelaPageRoutingModule } from './tela-routing.module';

import { TelaPage } from './tela.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    TelaPageRoutingModule
  ],
  declarations: [TelaPage]
})
export class TelaPageModule {}
