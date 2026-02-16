<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Money Tracker - Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
        }

        body.dark-mode {
            background: #0f1115;
            color: #e6e9ef;
        }

        body.dark-mode h1,
        body.dark-mode h2,
        body.dark-mode h3,
        body.dark-mode h4,
        body.dark-mode .category-name,
        body.dark-mode .current-month,
        body.dark-mode .user-name {
            color: #e6e9ef;
        }

        .hidden {
            display: none;
        }

        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-screen.hidden {
            display: none;
        }

        .loader {
            width: 60px;
            height: 60px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: white;
            font-size: 18px;
            margin-top: 20px;
            font-weight: 500;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-pic {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.5);
            object-fit: cover;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-pic:hover {
            border-color: rgba(255, 255, 255, 0.8);
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.05);
        }

        .profile-pic img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 16px;
        }



        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 8px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.3s;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card .icon {
            font-size: 36px;
            margin-bottom: 10px;
        }

        .stat-card .label {
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .stat-card .value {
            color: #333;
            font-size: 24px;
            font-weight: 600;
        }

        body.dark-mode .stat-card .label {
            color: #aab3c2;
        }

        body.dark-mode .stat-card .value {
            color: #f5f7fb;
        }

        body.dark-mode .stat-card .icon {
            color: #d7ddee;
        }

        .month-selector {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .month-selector button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.2s;
        }

        .month-selector button:hover {
            transform: scale(1.05);
        }

        .month-selector .current-month {
            font-size: 20px;
            font-weight: 600;
            color: #333;
            min-width: 200px;
            text-align: center;
        }

        .chart-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 968px) {
            .charts-row {
                grid-template-columns: 1fr;
            }
        }

        .chart-container h3 {
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        .chart-wrapper {
            max-width: 100%;
            margin: 0 auto;
            position: relative;
            height: 350px;
        }

        .chart-wrapper.pie {
            aspect-ratio: 1 / 1;
            height: auto;
            max-height: 380px;
        }

        .chart-wrapper canvas {
            width: 100% !important;
            height: 100% !important;
        }

        .categories-section {
            margin-top: 40px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h3 {
            color: #333;
            font-size: 20px;
        }

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .category-card {
            background: white;
            padding: 30px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
            border: 2px solid transparent;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .category-card.income {
            border-color: #4caf50;
        }

        .category-card.savings {
            border-color: #2196f3;
        }

        .category-card.debt {
            border-color: #ff9800;
        }

        .category-card.bills {
            border-color: #9c27b0;
        }

        .category-card.expenses {
            border-color: #f44336;
        }

        .category-card .category-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .category-card .category-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .category-card .category-amount {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .category-card.income .category-amount {
            color: #4caf50;
        }

        .category-card.savings .category-amount {
            color: #2196f3;
        }

        .category-card.debt .category-amount {
            color: #ff9800;
        }

        .category-card.bills .category-amount {
            color: #9c27b0;
        }

        .category-card.expenses .category-amount {
            color: #f44336;
        }

        .category-card .category-status {
            font-size: 12px;
            color: #666;
        }

        .category-section {
            display: none;
            margin-top: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .category-section.show {
            display: block;
        }

        .category-header {
            padding: 25px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .category-header h2 {
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 28px;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .close-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        .category-body {
            padding: 30px;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .summary-card {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-card .summary-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .summary-card .summary-value {
            font-size: 20px;
            font-weight: 700;
        }

        .entry-form {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            display: block;
        }

        .entry-form h4 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 5px;
            overflow: hidden;
        }

        .form-table td {
            padding: 12px;
            border: 1px solid #eee;
        }

        .form-table label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .form-table input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-table input:focus {
            outline: none;
            border-color: #667eea;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .btn-save {
            background: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-save:hover {
            background: #45a049;
        }

        .btn-cancel {
            background: #999;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.3s;
        }

        .btn-cancel:hover {
            background: #888;
        }

        .budget-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .budget-table th,
        .budget-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .budget-table th {
            background: #f9f9f9;
            font-weight: 600;
            color: #333;
            text-transform: uppercase;
            font-size: 12px;
        }

        .budget-table td {
            color: #666;
        }

        .budget-table tr:hover {
            background: #fafafa;
        }

        .amount {
            font-weight: 600;
            font-size: 16px;
        }

        .difference {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
            display: inline-block;
        }

        .difference.over {
            background: #ffebee;
            color: #c62828;
        }

        .difference.under {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .action-btn {
            background: #2196f3;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 5px;
            font-size: 12px;
            transition: background 0.3s;
        }

        .action-btn:hover {
            background: #1976d2;
        }

        .action-btn.delete {
            background: #f44336;
        }

        .action-btn.delete:hover {
            background: #d32f2f;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        body.dark-mode .stat-card,
        body.dark-mode .category-card,
        body.dark-mode .chart-container,
        body.dark-mode .category-section,
        body.dark-mode .summary-card,
        body.dark-mode .entry-form {
            background: #1b1f26;
            color: #e6e9ef;
            border-color: #2a2f3a;
        }

        body.dark-mode .category-status,
        body.dark-mode .summary-label,
        body.dark-mode .label,
        body.dark-mode .empty-state {
            color: #aab3c2;
        }

        body.dark-mode .month-selector,
        body.dark-mode .category-header,
        body.dark-mode .budget-table th {
            background: #1f2430;
            color: #e6e9ef;
        }

        body.dark-mode .budget-table td {
            border-bottom-color: #2a2f3a;
            color: #e6e9ef;
        }

        body.dark-mode .budget-table tr:hover {
            background: #222836;
        }

        body.dark-mode .form-table input {
            background: #141820;
            color: #e6e9ef;
            border-color: #2a2f3a;
        }

        body.dark-mode .form-table label {
            color: #cfd6e4;
        }

        @media (max-width: 768px) {
            .summary-cards {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .user-menu {
                width: 100%;
                justify-content: space-between;
                flex-wrap: wrap;
                gap: 12px;
            }

            .month-selector {
                flex-wrap: wrap;
                gap: 12px;
                padding: 16px;
            }

            .month-selector .current-month {
                min-width: unset;
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            .container {
                padding: 0 12px;
            }

            .charts-row {
                grid-template-columns: 1fr;
            }

            .chart-wrapper {
                height: 260px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .categories-grid {
                grid-template-columns: 1fr;
            }

            .category-card {
                padding: 22px 16px;
            }

            .entry-form {
                padding: 18px;
            }

            .budget-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .budget-table th,
            .budget-table td {
                padding: 10px;
                font-size: 12px;
            }

            .action-btn {
                margin-bottom: 6px;
                width: 100%;
            }
        }

        @media (max-width: 420px) {
            .header h1 {
                font-size: 20px;
            }

            .logout-btn {
                width: 100%;
            }

            .month-selector button {
                width: 100%;
            }
        }

        @media (min-width: 1200px) {
            .chart-wrapper {
                height: 380px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loader"></div>
        <div class="loading-text">Loading your financial data...</div>
    </div>

    <div class="header">
        <div class="header-content">
            <h1>💰 Money Tracker</h1>
            <div class="user-menu">
                <div class="profile-pic" onclick="window.location.href='edit-profile.php'" title="Edit Profile">
                    <img id="profilePicImg" alt="Profile" class="hidden">
                    <span id="profilePicFallback">👤</span>
                </div>
                <div class="user-info">
                    <div class="user-name" id="userName">User</div>
                </div>
                <button class="logout-btn" id="logoutBtn">Logout</button>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Month Selector -->
        <div class="month-selector">
            <button onclick="changeMonth(-1)">← Previous</button>
            <div class="current-month" id="currentMonth">January 2026</div>
            <button onclick="changeMonth(1)">Next →</button>
        </div>

        <!-- Charts Side by Side -->
        <div class="charts-row">
            <!-- Budget Pie Chart -->
            <div class="chart-container">
                <h3>💰 Budget Overview</h3>
                <div class="chart-wrapper pie">
                    <canvas id="budgetChart"></canvas>
                </div>
            </div>

            <!-- Income vs Expense Bar Chart -->
            <div class="chart-container">
                <h3>📊 Last 5 Months: Income vs Expenses</h3>
                <div class="chart-wrapper">
                    <canvas id="comparisonChart"></canvas>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon">💵</div>
                <div class="label">Total Income</div>
                <div class="value" id="totalIncome">$0.00</div>
            </div>
            <div class="stat-card">
                <div class="icon">💸</div>
                <div class="label">Total Expenses</div>
                <div class="value" id="totalExpenses">$0.00</div>
            </div>
            <div class="stat-card">
                <div class="icon">💰</div>
                <div class="label">Balance</div>
                <div class="value" id="balance">$0.00</div>
            </div>
            <div class="stat-card">
                <div class="icon">📊</div>
                <div class="label">Transactions</div>
                <div class="value" id="totalTransactions">0</div>
            </div>
        </div>

        <div class="categories-section">
            <div class="section-header">
                <h3>Manage Your Finances</h3>
            </div>

            <div class="categories-grid">
                <div class="category-card income" data-category="income" onclick="openCategoryModal('income')">
                    <div class="category-icon">💰</div>
                    <div class="category-name">Income</div>
                    <div class="category-amount" id="incomeAmount">$0.00</div>
                    <div class="category-status">Click to manage</div>
                </div>

                <div class="category-card savings" data-category="savings" onclick="openCategoryModal('savings')">
                    <div class="category-icon">🏦</div>
                    <div class="category-name">Savings</div>
                    <div class="category-amount" id="savingsAmount">$0.00</div>
                    <div class="category-status">Click to manage</div>
                </div>

                <div class="category-card debt" data-category="debt" onclick="openCategoryModal('debt')">
                    <div class="category-icon">💳</div>
                    <div class="category-name">Debt</div>
                    <div class="category-amount" id="debtAmount">$0.00</div>
                    <div class="category-status">Click to manage</div>
                </div>

                <div class="category-card bills" data-category="bills" onclick="openCategoryModal('bills')">
                    <div class="category-icon">📄</div>
                    <div class="category-name">Bills</div>
                    <div class="category-amount" id="billsAmount">$0.00</div>
                    <div class="category-status">Click to manage</div>
                </div>

                <div class="category-card expenses" data-category="expenses" onclick="openCategoryModal('expenses')">
                    <div class="category-icon">🛒</div>
                    <div class="category-name">Expenses</div>
                    <div class="category-amount" id="expensesAmount">$0.00</div>
                    <div class="category-status">Click to manage</div>
                </div>
            </div>
        </div>

        <div id="categorySection" class="category-section">
            <div class="category-header">
                <h2>
                    <span id="modalIcon">💰</span>
                    <span id="modalTitle">Income</span>
                </h2>
                <button class="close-btn" onclick="closeCategorySection()">&times;</button>
            </div>
            <div class="category-body">
                <div class="summary-cards">
                    <div class="summary-card">
                        <div class="summary-label">Budget</div>
                        <div class="summary-value" id="modalBudgetTotal">$0.00</div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label">Actual</div>
                        <div class="summary-value" id="modalActualTotal">$0.00</div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-label">Difference</div>
                        <div class="summary-value" id="modalDifference">$0.00</div>
                    </div>
                </div>

                <div class="entry-form" id="entryForm">
                    <h4 id="formTitle">Add New Entry</h4>
                    <table class="form-table">
                        <tr>
                            <td>
                                <label>Description *</label>
                                <input type="text" id="inputDescription" placeholder="e.g., Monthly Salary, Rent, Groceries" required>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <label>Budget Amount *</label>
                                <input type="number" id="inputBudget" placeholder="0.00" step="0.01" min="0" required>
                            </td>
                            <td style="width: 50%;">
                                <label>Actual Amount *</label>
                                <input type="number" id="inputActual" placeholder="0.00" step="0.01" min="0" required>
                            </td>
                        </tr>
                    </table>
                    <div class="form-actions">
                        <button class="btn-save" onclick="saveEntry()">Save Entry</button>
                        <button class="btn-cancel" onclick="clearEntryForm()">Clear</button>
                    </div>
                </div>

                <table class="budget-table" id="budgetTable">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Budget</th>
                            <th>Actual</th>
                            <th>Difference</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Populated by JavaScript -->
                    </tbody>
                </table>

                <div id="emptyState" class="empty-state" style="display: none;">
                    <div class="empty-state-icon">📋</div>
                    <p>No entries yet. Use the form above to add your first entry.</p>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-app.js";
        import { getAuth, onAuthStateChanged, signOut } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-auth.js";
        import { getFirestore, doc, getDoc, setDoc, serverTimestamp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-firestore.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCxMoF0mTrZYej5K8h1_MkXQ3eKQ-4FZvE",
            authDomain: "moneytracker-c1dd1.firebaseapp.com",
            projectId: "moneytracker-c1dd1",
            storageBucket: "moneytracker-c1dd1.firebasestorage.app",
            messagingSenderId: "71356341269",
            appId: "1:71356341269:web:8ae54dbdfebe06acd3c21c",
            measurementId: "G-49036TSCHY"
        };

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);
        const db = getFirestore(app);

        let userId = null;
        let currentCategory = '';
        let editingIndex = -1;
        
        // Current selected month and year
        const now = new Date();
        let currentMonth = now.getMonth(); // 0-11
        let currentYear = now.getFullYear();

        let categoryData = {
            income: [],
            savings: [],
            debt: [],
            bills: [],
            expenses: []
        };

        const categoryConfig = {
            income: { icon: '💰', title: 'Income' },
            savings: { icon: '🏦', title: 'Savings' },
            debt: { icon: '💳', title: 'Debt' },
            bills: { icon: '📄', title: 'Bills' },
            expenses: { icon: '🛒', title: 'Expenses' }
        };

        // Initialize Charts
        let budgetChart = null;
        let comparisonChart = null;

        const defaultSettings = {
            currency: 'USD',
            darkMode: false
        };
        let userSettings = { ...defaultSettings };
        let currencySymbol = '$';

        function applySettings(settings) {
            userSettings = { ...defaultSettings, ...settings };
            currencySymbol = userSettings.currency === 'PHP' ? 'PHP ' : '$';
            document.body.classList.toggle('dark-mode', !!userSettings.darkMode);
            if (typeof Chart !== 'undefined') {
                Chart.defaults.color = userSettings.darkMode ? '#e6e9ef' : '#333';
                Chart.defaults.borderColor = userSettings.darkMode ? '#2a2f3a' : '#e0e0e0';
            }
            updateDashboard();
            updateBudgetChart();
            updateComparisonChart();
        }

        // Month management
        function updateMonthDisplay() {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'];
            document.getElementById('currentMonth').textContent = `${monthNames[currentMonth]} ${currentYear}`;
        }

        window.changeMonth = function(direction) {
            currentMonth += direction;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            } else if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            updateMonthDisplay();
            updateDashboard();
            if (currentCategory) {
                renderTable();
                updateModalSummary();
            }
        };

        function getMonthKey(month, year) {
            return `${year}-${String(month + 1).padStart(2, '0')}`;
        }

        function getCurrentMonthKey() {
            return getMonthKey(currentMonth, currentYear);
        }

        function calculateTotal(category) {
            const entries = categoryData[category] || [];
            const monthKey = getCurrentMonthKey();
            return entries
                .filter(entry => entry.month === monthKey)
                .reduce((sum, entry) => sum + (parseFloat(entry.actual) || 0), 0);
        }

        function initBudgetChart() {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js library not loaded');
                return;
            }
            
            const canvas = document.getElementById('budgetChart');
            if (!canvas) {
                console.error('Canvas element not found');
                return;
            }
            
            console.log('Initializing budget chart...');
            
            const ctx = canvas.getContext('2d');
            budgetChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['No Data'],
                    datasets: [{
                        data: [1],
                        backgroundColor: ['#e0e0e0'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    aspectRatio: 1,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    if (label === 'No Data') {
                                        return 'No budget data yet';
                                    }
                                    return label + ': ' + formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
            
            console.log('Budget chart initialized successfully');
        }

        function initComparisonChart() {
            if (typeof Chart === 'undefined') {
                console.error('Chart.js library not loaded');
                return;
            }
            
            const canvas = document.getElementById('comparisonChart');
            if (!canvas) {
                console.error('Comparison canvas element not found');
                return;
            }
            
            console.log('Initializing comparison chart...');
            
            const ctx = canvas.getContext('2d');
            comparisonChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Income',
                            data: [],
                            backgroundColor: '#4caf50',
                            borderColor: '#388e3c',
                            borderWidth: 1
                        },
                        {
                            label: 'Expenses',
                            data: [],
                            backgroundColor: '#f44336',
                            borderColor: '#d32f2f',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + formatCurrency(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return formatCurrency(value, 0);
                                }
                            }
                        }
                    }
                }
            });
            
            console.log('Comparison chart initialized successfully');
        }

        function updateComparisonChart() {
            if (!comparisonChart) {
                console.warn('Comparison chart not initialized yet');
                return;
            }

            const labels = [];
            const incomeData = [];
            const expenseData = [];

            // Get last 5 months including current month
            for (let i = 4; i >= 0; i--) {
                let month = currentMonth - i;
                let year = currentYear;
                
                // Handle year boundary
                while (month < 0) {
                    month += 12;
                    year--;
                }
                while (month > 11) {
                    month -= 12;
                    year++;
                }
                
                const monthKey = getMonthKey(month, year);
                const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                    'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                
                labels.push(`${monthNames[month]} ${year}`);
                
                // Calculate income for this month
                const incomeEntries = categoryData.income.filter(e => e.month === monthKey);
                const income = incomeEntries.reduce((sum, e) => sum + (parseFloat(e.actual) || 0), 0);
                incomeData.push(income);
                
                // Calculate total expenses for this month
                let expenses = 0;
                ['savings', 'debt', 'bills', 'expenses'].forEach(category => {
                    const catEntries = categoryData[category].filter(e => e.month === monthKey);
                    expenses += catEntries.reduce((sum, e) => sum + (parseFloat(e.actual) || 0), 0);
                });
                expenseData.push(expenses);
            }

            comparisonChart.data.labels = labels;
            comparisonChart.data.datasets[0].data = incomeData;
            comparisonChart.data.datasets[1].data = expenseData;
            comparisonChart.update();
            
            console.log('Comparison chart updated successfully');
        }

        function updateBudgetChart() {
            if (!budgetChart) {
                console.warn('Budget chart not initialized yet');
                return;
            }

            const income = calculateTotal('income');
            const savings = calculateTotal('savings');
            const debt = calculateTotal('debt');
            const bills = calculateTotal('bills');
            const expenses = calculateTotal('expenses');
            
            const totalSpent = savings + debt + bills + expenses;
            const availableBalance = income - totalSpent;

            console.log('Updating chart:', { income, savings, debt, bills, expenses, availableBalance });

            // Only show categories with values
            const labels = [];
            const data = [];
            const colors = [];

            if (availableBalance > 0) {
                labels.push('Available Balance');
                data.push(availableBalance);
                colors.push('#4caf50');
            }

            if (savings > 0) {
                labels.push('Savings');
                data.push(savings);
                colors.push('#2196f3');
            }

            if (debt > 0) {
                labels.push('Debt');
                data.push(debt);
                colors.push('#ff9800');
            }

            if (bills > 0) {
                labels.push('Bills');
                data.push(bills);
                colors.push('#9c27b0');
            }

            if (expenses > 0) {
                labels.push('Expenses');
                data.push(expenses);
                colors.push('#f44336');
            }

            // If no data, show placeholder
            if (data.length === 0) {
                labels.push('No Data');
                data.push(1);
                colors.push('#e0e0e0');
            }

            budgetChart.data.labels = labels;
            budgetChart.data.datasets[0].data = data;
            budgetChart.data.datasets[0].backgroundColor = colors;
            budgetChart.update();
            
            console.log('Chart updated successfully');
        }

        window.openCategoryModal = function(category) {
            currentCategory = category;
            const config = categoryConfig[category];

            document.getElementById('modalIcon').textContent = config.icon;
            document.getElementById('modalTitle').textContent = config.title;

            clearEntryForm();
            renderTable();
            updateModalSummary();

            const section = document.getElementById('categorySection');
            section.classList.add('show');
            section.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        window.closeCategorySection = function() {
            document.getElementById('categorySection').classList.remove('show');
        };

        window.saveEntry = function() {
            if (!currentCategory) {
                alert('Please select a category first.');
                return;
            }

            const description = document.getElementById('inputDescription').value.trim();
            const budget = document.getElementById('inputBudget').value;
            const actual = document.getElementById('inputActual').value;

            if (!description) {
                alert('Please enter a description');
                document.getElementById('inputDescription').focus();
                return;
            }

            if (!budget || parseFloat(budget) < 0) {
                alert('Please enter a valid budget amount');
                document.getElementById('inputBudget').focus();
                return;
            }

            if (!actual || parseFloat(actual) < 0) {
                alert('Please enter a valid actual amount');
                document.getElementById('inputActual').focus();
                return;
            }

            const entry = {
                description: description,
                budget: parseFloat(budget),
                actual: parseFloat(actual),
                month: getCurrentMonthKey()  // Add month tracking
            };

            if (editingIndex >= 0) {
                categoryData[currentCategory][editingIndex] = entry;
            } else {
                categoryData[currentCategory].push(entry);
            }

            saveData();
            clearEntryForm();
        };

        window.clearEntryForm = function() {
            editingIndex = -1;
            document.getElementById('formTitle').textContent = 'Add New Entry';
            document.getElementById('inputDescription').value = '';
            document.getElementById('inputBudget').value = '';
            document.getElementById('inputActual').value = '';
        };

        window.editEntry = function(index) {
            const entry = categoryData[currentCategory][index];

            document.getElementById('formTitle').textContent = 'Edit Entry';
            document.getElementById('inputDescription').value = entry.description;
            document.getElementById('inputBudget').value = entry.budget;
            document.getElementById('inputActual').value = entry.actual;

            editingIndex = index;
            document.getElementById('inputDescription').focus();
        };

        window.deleteEntry = function(index) {
            if (!confirm('Are you sure you want to delete this entry?')) return;

            categoryData[currentCategory].splice(index, 1);
            saveData();
        };

        document.getElementById('logoutBtn').addEventListener('click', async function() {
            await signOut(auth);
            window.location.href = '../index.html';
        });

        onAuthStateChanged(auth, async (user) => {
            if (!user) {
                window.location.href = '../index.html';
                return;
            }

            userId = user.uid;

            try {
                // Initialize month display
                updateMonthDisplay();
                
                await loadProfile(user);
                await loadFinances();
                
                // Initialize charts after DOM is ready and data is loaded
                setTimeout(() => {
                    initBudgetChart();
                    initComparisonChart();
                    updateBudgetChart();
                    updateComparisonChart();
                }, 100);
            } finally {
                // Hide loading screen after everything is loaded
                document.getElementById('loadingScreen').classList.add('hidden');
            }
        });

        async function loadProfile(user) {
            try {
                const profileRef = doc(db, 'users', user.uid);
                const profileSnap = await getDoc(profileRef);

                if (profileSnap.exists()) {
                    const profile = profileSnap.data();
                    const fullName = profile.fullName || 'User';

                    document.getElementById('userName').textContent = fullName;

                    applySettings(profile.settings || {});

                    if (profile.profilePicture) {
                        const img = document.getElementById('profilePicImg');
                        img.src = profile.profilePicture;
                        img.classList.remove('hidden');
                        document.getElementById('profilePicFallback').classList.add('hidden');
                    }
                } else {
                    const displayName = user.displayName || 'User';
                    document.getElementById('userName').textContent = displayName;
                    applySettings({});
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        async function loadFinances() {
            try {
                const financeRef = doc(db, 'finances', userId);
                const financeSnap = await getDoc(financeRef);

                if (financeSnap.exists()) {
                    const data = financeSnap.data();
                    const currentMonthKey = getCurrentMonthKey();
                    
                    // Add month key to existing entries that don't have one
                    categoryData = {
                        income: (data.income || []).map(e => e.month ? e : {...e, month: currentMonthKey}),
                        savings: (data.savings || []).map(e => e.month ? e : {...e, month: currentMonthKey}),
                        debt: (data.debt || []).map(e => e.month ? e : {...e, month: currentMonthKey}),
                        bills: (data.bills || []).map(e => e.month ? e : {...e, month: currentMonthKey}),
                        expenses: (data.expenses || []).map(e => e.month ? e : {...e, month: currentMonthKey})
                    };
                }
            } catch (error) {
                console.error('Error loading finances:', error);
            }

            updateDashboard();
        }

        async function saveData() {
            try {
                const financeRef = doc(db, 'finances', userId);
                await setDoc(financeRef, {
                    ...categoryData,
                    updatedAt: serverTimestamp()
                }, { merge: true });

                renderTable();
                updateModalSummary();
                updateDashboard();
            } catch (error) {
                console.error('Error saving data:', error);
                alert('Error saving data. Check Firestore rules.');
            }
        }

        function updateDashboard() {
            let totalIncome = 0;
            let totalExpenses = 0;
            const monthKey = getCurrentMonthKey();

            Object.keys(categoryData).forEach(category => {
                const allEntries = categoryData[category];
                const monthEntries = allEntries.filter(entry => entry.month === monthKey);
                const total = monthEntries.reduce((sum, entry) => sum + (parseFloat(entry.actual) || 0), 0);

                const amountElement = document.getElementById(`${category}Amount`);
                if (amountElement) {
                    amountElement.textContent = formatCurrency(total);
                }

                if (category === 'income') {
                    totalIncome += total;
                } else {
                    totalExpenses += total;
                }
            });

            document.getElementById('totalIncome').textContent = formatCurrency(totalIncome);
            document.getElementById('totalExpenses').textContent = formatCurrency(totalExpenses);
            document.getElementById('balance').textContent = formatCurrency(totalIncome - totalExpenses);

            const totalTransactions = Object.values(categoryData)
                .reduce((sum, cat) => sum + cat.filter(entry => entry.month === monthKey).length, 0);
            document.getElementById('totalTransactions').textContent = totalTransactions;

            // Update charts
            updateBudgetChart();
            updateComparisonChart();
        }

        function renderTable() {
            const tbody = document.getElementById('tableBody');
            const allEntries = categoryData[currentCategory] || [];
            const monthKey = getCurrentMonthKey();
            const entries = allEntries.filter(entry => entry.month === monthKey);

            if (!currentCategory) {
                tbody.innerHTML = '';
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('budgetTable').style.display = 'none';
                return;
            }

            if (entries.length === 0) {
                tbody.innerHTML = '';
                document.getElementById('emptyState').style.display = 'block';
                document.getElementById('budgetTable').style.display = 'none';
                return;
            }

            document.getElementById('emptyState').style.display = 'none';
            document.getElementById('budgetTable').style.display = 'table';

            tbody.innerHTML = entries.map((entry, index) => {
                // Find the actual index in the full array for edit/delete
                const actualIndex = allEntries.findIndex(e => 
                    e.description === entry.description && 
                    e.budget === entry.budget && 
                    e.actual === entry.actual &&
                    e.month === entry.month
                );
                
                const budget = parseFloat(entry.budget) || 0;
                const actual = parseFloat(entry.actual) || 0;
                const difference = actual - budget;
                const diffClass = difference > 0 ? 'over' : 'under';
                const diffText = difference > 0 ? `+${formatCurrency(difference)}` : formatCurrency(difference);

                return `
                    <tr>
                        <td>${escapeHtml(entry.description)}</td>
                        <td class="amount">${formatCurrency(budget)}</td>
                        <td class="amount">${formatCurrency(actual)}</td>
                        <td><span class="difference ${diffClass}">${diffText}</span></td>
                        <td>
                            <button class="action-btn" onclick="editEntry(${actualIndex})">✏️ Edit</button>
                            <button class="action-btn delete" onclick="deleteEntry(${actualIndex})">🗑️ Delete</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function updateModalSummary() {
            const allEntries = categoryData[currentCategory] || [];
            const monthKey = getCurrentMonthKey();
            const entries = allEntries.filter(entry => entry.month === monthKey);
            
            const budgetTotal = entries.reduce((sum, entry) => sum + (parseFloat(entry.budget) || 0), 0);
            const actualTotal = entries.reduce((sum, entry) => sum + (parseFloat(entry.actual) || 0), 0);
            const difference = actualTotal - budgetTotal;

            document.getElementById('modalBudgetTotal').textContent = formatCurrency(budgetTotal);
            document.getElementById('modalActualTotal').textContent = formatCurrency(actualTotal);
            document.getElementById('modalDifference').textContent = formatCurrency(difference);
            document.getElementById('modalDifference').style.color = difference >= 0 ? '#4caf50' : '#f44336';
        }

        function formatCurrency(amount, decimals = 2) {
            const value = parseFloat(amount) || 0;
            return currencySymbol + value.toFixed(decimals).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
