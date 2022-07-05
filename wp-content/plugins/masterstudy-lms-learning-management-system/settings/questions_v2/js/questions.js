/**
 * @var stm_lms_questions
 */

Vue.component('stm_questions_v2', {
    props: ['posts', 'stored_ids'],
    components: [],
    data() {
        return {
            loaded: true,
            isSearch: false,
            tab: 'new_quiz',
            tabs: [
                ''
            ],
            choices: {
                'single_choice': 'Single choice',
                'multi_choice': 'Multi choice',
                'true_false': 'True or false',
                'item_match': 'Item Match',
                'image_match': 'Item Match',
                'keywords': 'Keywords',
                'fill_the_gap': 'Fill the Gap',
            },
            mediaLibrary: false,
            checkedItem: {},
            items: [],
            question_types: [],
            add_new_question: '',
            add_new_question_type: 'single_choice',
            loading: false,
            search: '',
            searching: false,
            searchTimeout: '',
            options: [],
            question_type: 'single_choice',


            question_bank_title: '',
            question_bank: stm_lms_questions,
            question_bank_category: {},
            question_bank_num: 5,
        };
    },
    mounted: function () {
        var _this = this;

        if(typeof stm_lms_question_choices !== 'undefined') {
            _this.$set(_this, 'choices', stm_lms_question_choices);
        }

        _this.initComponent();

        if (typeof WPCFTO_EventBus !== 'undefined') {
            WPCFTO_EventBus.$on('STM_LMS_Questions_Update', function (item) {
                _this.stored_ids = item;
                _this.initComponent();
            });
        }
    },
    methods: {
        closeModal() {
            this.mediaLibrary = false
        },

        openMedia(item) {
            this.checkedItem = item
            this.showMediaLibrary();
        },

        checkedImage(image) {
            this.checkedItem.image = {
                id: image.id,
                url: image.url
            }
            this.mediaLibrary = false
        },
        showMediaLibrary() {
            this.mediaLibrary = !this.mediaLibrary
        },
        initComponent() {
            if (this.stored_ids) {
                this.getPosts(stm_wpcfto_ajaxurl + '?action=wpcfto_search_posts&nonce=' + stm_wpcfto_nonces['wpcfto_search_posts'] + '&posts_per_page=-1&orderby=post__in&ids=' + this.stored_ids + '&post_types=' + this.posts.join(','), 'items');
            } else {
                this.items = [];
                this.isLoading(false);
            }
        },
        getPosts(url, variable) {
            var vm = this;
            vm.isLoading(true);
            this.$http.get(url).then(function (response) {

                vm[variable] = response.body;
                response.body.forEach(function (question) {
                    question.title = decodeEntities(question.title);
                });
                vm.isLoading(false);
            });
        },
        isLoading(isLoading) {
            this.loading = isLoading;
        },
        createQuestion() {
            var vm = this;
            if (vm.add_new_question === '') return false;
            vm.isLoading(true);
            var isFront = typeof stm_lms_manage_course_id !== 'undefined';

            var url = stm_wpcfto_ajaxurl + '?action=stm_curriculum_create_item&nonce=' + stm_lms_nonces['stm_curriculum_create_item'];
            url += '&post_type=' + this.posts.join(',') + '&is_front=' + isFront + '&title=' + encodeURIComponent(vm.add_new_question);

            this.$http.get(url).then(function (response) {
                var item = response.body;
                item.type = vm.add_new_question_type;
                this.items.push(response.body);
                vm.add_new_question = '';
                vm.isLoading(false);
            });
        },
        deleteQuestion(item_key, message) {
            if (!confirm(message)) return false;
            this.items.splice(item_key, 1);

            /*For deep watcher*/
            this.items = this.items;
        },
        saveQuestions() {
            var vm = this;

            var $ = jQuery;
            var $publish_button = $('#publishing-action input[name="save"]');

            $publish_button.attr('disabled', 1);

            this.$http.post(stm_wpcfto_ajaxurl + '?action=stm_save_questions&nonce=' + stm_lms_nonces['stm_save_questions'], JSON.stringify(vm.items)).then(function () {
                $publish_button.removeAttr('disabled');
            }, function () {
                $publish_button.removeAttr('disabled');
            });
        },
        updateIds() {
            var vm = this;
            vm.ids = [];
            this.items.forEach(function (value, key) {
                if (typeof value !== 'undefined') {
                    vm.ids.push(value.id);
                }
            });
            vm.$emit('get-questions', vm.ids);
        },
        changeTitle(post_id, title, itemKey) {
            if (isNaN(post_id)) {
                this.items[itemKey]['id'] = title;
                this.updateIds();
            } else {
                this.$http.get(stm_wpcfto_ajaxurl + '?action=stm_save_title&nonce=' + stm_lms_nonces['stm_save_title'] + '&title=' + encodeURIComponent(title) + '&id=' + post_id);
            }
        },
        addToBank(term) {
            var _this = this;

            if (typeof (_this.question_bank_category[term.slug]) === 'undefined') {

                _this.$set(_this.question_bank_category, term.slug, term);

            } else {

                _this.$set(_this.question_bank_category, term.slug);

            }
        },
        addQB() {
            var vm = this;
            if (vm.question_bank_title === '') return false;
            vm.isLoading(true);

            var url = stm_wpcfto_ajaxurl + '?action=stm_curriculum_create_item&nonce=' + stm_lms_nonces['stm_curriculum_create_item'];
            url += '&post_type=' + this.posts.join(',') + '&title=' + vm.question_bank_title;

            /*Rebuild categories to array without keys*/
            let categories = [];
            Object.keys(vm.question_bank_category).forEach(key => {
                categories.push(vm.question_bank_category[key]);
            });

            this.$http.get(url).then(function (response) {
                var item = response.body;
                item.type = 'question_bank';
                var answer = [{
                    'categories': categories,
                    'number': vm.question_bank_num
                }];

                vm.$set(item, 'answers', answer);

                vm.items.push(response.body);
                vm.question_bank_title = '';

                vm.$set(vm, 'question_bank_category', {});
                vm.isLoading(false);
            });
        }
    },
    watch: {
        items: {
            handler: function () {
                var vm = this;

                vm.updateIds();

                clearTimeout(vm.timer);
                vm.timer = setTimeout(function () {
                    vm.saveQuestions();
                }, 500);
            },
            deep: true
        }
    },
});

var decodeEntities = (function() {
    // this prevents any overhead from creating the object each time
    var element = document.createElement('div');

    function decodeHTMLEntities (str) {
        if(str && typeof str === 'string') {
            // strip script/html tags
            str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
            str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
            element.innerHTML = str;
            str = element.textContent;
            element.textContent = '';
        }

        return str;
    }

    return decodeHTMLEntities;
})();