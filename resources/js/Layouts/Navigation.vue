<template>
    <aside
        class="z-20 hidden w-64 overflow-y-auto bg-white md:block flex-shrink-0"
    >
        <div class="py-4 text-gray-500">
            <Link
                class="ml-6 text-lg font-bold text-gray-800"
                :href="route('dashboard')"
            >
                Co-Po
            </Link>

            <ul class="mt-6">
                <!-- student route start -->
                <li
                    class="relative px-6 py-3"
                    v-if="$page.props.auth.user && !$page.props.auth.user.role"
                >
                    <BreezeNavLink
                        :href="route('student.dashboard')"
                        :active="route().current('student.dashboard')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                                />
                            </svg>
                        </template>
                        Dashboard
                    </BreezeNavLink>
                </li>
                <li
                    class="relative px-6 py-3"
                    v-if="$page.props.auth.user && !$page.props.auth.user.role"
                >
                    <BreezeNavLink
                        :href="route('student.markShowIndex')"
                        :active="route().current('student.markShowIndex')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                />
                            </svg>
                        </template>
                        Get My Result
                    </BreezeNavLink>
                </li>
                <li
                    class="relative px-6 py-3"
                    v-if="$page.props.auth.user && !$page.props.auth.user.role"
                >
                    <BreezeNavLink
                        :href="route('student.completePoIndex')"
                        :active="route().current('student.completePoIndex')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </template>
                        Completed Po
                    </BreezeNavLink>
                </li>
                <!-- student route end -->

                <li
                    class="relative px-6 py-3"
                    v-if="roleCheck(['ADMIN', 'TEACHER'])"
                >
                    <BreezeNavLink
                        :href="route('dashboard')"
                        :active="route().current('dashboard')"
                    >
                        <template #icon>
                            <svg
                                class="w-5 h-5"
                                aria-hidden="true"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                ></path>
                            </svg>
                        </template>
                        Dashboard
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <BreezeNavLink
                        :href="route('users.index')"
                        :active="route().current('users.index')"
                    >
                        <template #icon>
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                ></path>
                            </svg>
                        </template>
                        Users
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['TEACHER'])">
                    <BreezeNavLink
                        :href="route('course.teacher.myCourse')"
                        :active="route().current('course.teacher.myCourse')"
                    >
                        <template #icon>
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                ></path>
                            </svg>
                        </template>
                        My Course
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['TEACHER'])">
                    <BreezeNavLink
                        :href="route('course.teacher.myCompletedCourse')"
                        :active="
                            route().current('course.teacher.myCompletedCourse')
                        "
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
                                />
                            </svg>
                        </template>
                        My Completed Course
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <BreezeNavLink
                        :href="route('department.index')"
                        :active="route().current('department.index')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
                                />
                            </svg>
                        </template>
                        Department
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <BreezeNavLink
                        :href="route('sessions.index')"
                        :active="route().current('sessions.index')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </template>
                        Session
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <BreezeNavLink
                        :href="route('semesters.index')"
                        :active="route().current('semesters.index')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                /></svg
                        ></template>
                        Semester
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <BreezeNavLink
                        :href="route('batches.index')"
                        :active="route().current('batches.index')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"
                                />
                            </svg>
                        </template>
                        Batch
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <button
                        @click="showingTwoLevelMenu = !showingTwoLevelMenu"
                        class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                        aria-haspopup="true"
                    >
                        <span class="inline-flex items-center">
                            <svg
                                class="w-5 h-5"
                                aria-hidden="true"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"
                                ></path>
                            </svg>
                            <span class="ml-4">Co & Po</span>
                        </span>
                        <svg
                            class="w-4 h-4"
                            aria-hidden="true"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            ></path>
                        </svg>
                    </button>
                    <ul
                        v-show="showingTwoLevelMenu"
                        class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                        aria-label="submenu"
                    >
                        <li
                            class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            <Link
                                class="w-full"
                                :href="route('co.index')"
                                :active="route().current('co.index')"
                                >Course Outcome (CO)</Link
                            >
                        </li>
                        <li
                            class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                        >
                            <Link
                                class="w-full"
                                :href="route('po.index')"
                                :active="route().current('po.index')"
                                >Program Outcome (PO)</Link
                            >
                        </li>
                    </ul>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <BreezeNavLink
                        :href="route('course.index')"
                        :active="route().current('course.index')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                />
                            </svg>
                        </template>
                        Subject
                    </BreezeNavLink>
                </li>

                <li class="relative px-6 py-3" v-if="roleCheck(['DEAN'])">
                    <BreezeNavLink
                        :href="route('exam.markStudentIndex')"
                        :active="route().current('exam.markStudentIndex')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"
                                />
                            </svg>
                        </template>
                        Course Result
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['DEAN'])">
                    <BreezeNavLink
                        :href="route('exam.adminBatchIndex')"
                        :active="route().current('exam.adminBatchIndex')"
                    >
                        <template #icon>
                            <svg
                                class="w-5 h-5"
                                aria-hidden="true"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"
                                ></path>
                            </svg>
                        </template>
                        Batch Result
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['DEAN'])">
                    <BreezeNavLink
                        :href="route('exam.adminBatchPoorIndex')"
                        :active="route().current('exam.adminBatchPoorIndex')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </template>
                        Poor Student
                    </BreezeNavLink>
                </li>

                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <BreezeNavLink
                        :href="route('course.assignTeacherShow')"
                        :active="route().current('course.assignTeacherShow')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"
                                />
                            </svg>
                        </template>
                        Assign Teacher
                    </BreezeNavLink>
                </li>
                <li class="relative px-6 py-3" v-if="roleCheck(['ADMIN'])">
                    <BreezeNavLink
                        :href="route('users.studentShow')"
                        :active="route().current('users.studentShow')"
                    >
                        <template #icon>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </template>
                        Student Create
                    </BreezeNavLink>
                </li>
                <!-- <li class="relative px-6 py-3">
                    <button @click="menuSubject = !menuSubject"
                        class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800"
                        aria-haspopup="true">
                        <span class="inline-flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            <span class="ml-4">Subject</span>
                        </span>
                        <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <ul v-show="menuSubject"
                        class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                        aria-label="submenu">
                        <li
                            class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                            <a class="w-full" :href="route('course.index')"
                                :active="route().current('course.index')">Courses</a>
                        </li>
                        <li
                            class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                            <a class="w-full" :href="route('po.index')" :active="route().current('po.index')">Course Co Po</a>
                        </li>
                    </ul>
                </li> -->
            </ul>
        </div>
    </aside>
</template>

<script>
import BreezeNavLink from "@/Components/NavLink.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default {
    components: {
        BreezeNavLink,
        Link,
    },
    data() {
        return {
            showingTwoLevelMenu: false,
            menuSubject: false,
            role: "TEACHER",
        };
    },
    mounted() {
        let props = this.$page.props;
        this.role = props.auth && props.auth.user.role;
    },
    methods: {
        roleCheck(role) {
            return role.includes(this.role);
        },
    },
};
</script>
