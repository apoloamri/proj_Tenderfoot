<?php $this->Partial("navigation") ?>
<div id="adminPages">
    <div id="adminContent">
        <div id="adminInnerContent">
            <div id="add">
                <h2>Store Settings</h2>
                <div class="adminTable">
                    <h3>Index Page</h3>
                    <label>Announcement</label>
                    <textarea v-model="announcement" placeholder="Website announcement header..."></textarea>
                    <label class="red">{{messages["Announcement"]}}</label>
                    <label>Header</label>
                    <input type="file" v-on:change="UploadHeader()" id="header" />
                    <button v-on:click="DeleteHeader" class="gray inline-block">Remove Images</button>
                    <label class="red">{{messages["HeaderImage"]}}</label>
                    <div 
                        v-if="store.str_header != null && store.str_header != ''" 
                        v-bind:style="{ 'background-image': 'url(' + store.str_header + ')' }"
                        id="header" class="image size-100" >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/Resources/js/admin/store.js" async></script>